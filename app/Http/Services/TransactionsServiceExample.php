<?php

namespace App\Http\Services;

use App\Models\Preference;
use App\Models\Products;
use App\Models\RfidCards;
use App\Models\Transactions;
use App\Models\TransactionItems;
use App\Support\SimpleRSA;
use Illuminate\Support\Facades\Storage;

/**
 * CONTOH PENGGUNAAN SimpleRSA di TransactionsService
 * 
 * Jika Anda ingin menggunakan SimpleRSA untuk enkripsi transaksi,
 * Anda dapat mengganti method rsaEncrypt() dan rsaDecrypt() 
 * dengan simpleRsaEncrypt() dan simpleRsaDecrypt().
 * 
 * Contoh perubahan di method storeNewTransaction():
 * 
 * SEBELUM:
 * 'total_amount' => $this->rsaEncrypt($product->price),
 * 
 * SESUDAH:
 * 'total_amount' => $this->simpleRsaEncrypt($product->price),
 * 
 * Dan untuk dekripsi:
 * 
 * SEBELUM:
 * $decrypted = (float) $this->rsaDecrypt($transaction->total_amount);
 * 
 * SESUDAH:
 * $decrypted = (float) $this->simpleRsaDecrypt($transaction->total_amount);
 */

class TransactionsServiceExample
{
    /**
     * Get SimpleRSA instance from database keys
     */
    protected function getSimpleRSA(): SimpleRSA
    {
        $n = Preference::where('name', 'rsa_n')->value('value');
        $d = Preference::where('name', 'rsa_d')->value('value');
        $e = Preference::where('name', 'rsa_e')->value('value');

        if (!$n || !$d || !$e) {
            throw new \RuntimeException('RSA keys not found in database. Please configure encryption settings.');
        }

        return new SimpleRSA($n, $d, $e);
    }

    /**
     * Encrypt using SimpleRSA
     */
    protected function simpleRsaEncrypt(string $plaintext): string
    {
        return $this->getSimpleRSA()->encrypt($plaintext);
    }

    /**
     * Decrypt using SimpleRSA
     */
    protected function simpleRsaDecrypt(string $ciphertext): string
    {
        return $this->getSimpleRSA()->decrypt($ciphertext);
    }

    /* Store New Transaction - CONTOH PENGGUNAAN */
    public function storeNewTransactionExample(array $dataValidated)
    {
        $product = Products::find($dataValidated['product_id']);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        if ($product->stock < 1) {
            return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
        }

        try {
            \DB::beginTransaction();

            // GUNAKAN simpleRsaEncrypt untuk enkripsi dengan SimpleRSA
            $transaction = Transactions::create([
                'cashier_id' => auth()->id(),
                'total_amount' => $this->simpleRsaEncrypt($product->price), // <-- PERUBAHAN DI SINI
                'payment_method' => 'rfid',
                'status' => 'draft',
            ]);

            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
                'product_price' => $this->simpleRsaEncrypt($product->price), // <-- PERUBAHAN DI SINI
            ]);

            $product->stock -= 1;
            $product->save();

            \DB::commit();

            return redirect()->route('transactions.index', $transaction->id)
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    /* Update Transaction Item - CONTOH PENGGUNAAN */
    public function updateTransactionItemExample(array $dataValidated, string $transactionItemId)
    {
        $transactionItem = TransactionItems::find($transactionItemId);
        
        if (!$transactionItem) {
            return redirect()->back()->with('error', 'Transaction item not found.');
        }

        if ($dataValidated['quantity'] < 1) {
            return redirect()->back()->with('error', 'Quantity must be at least 1.');
        }

        $product = $transactionItem->product;
        $quantityOld = $transactionItem->quantity;
        $availableStock = $product->stock + $quantityOld;

        if ($dataValidated['quantity'] > $availableStock) {
            return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
        }

        try {
            \DB::beginTransaction();

            $transactionItem->update([
                'quantity' => $dataValidated['quantity'],
                'product_price' => $this->simpleRsaEncrypt($product->price), // <-- PERUBAHAN DI SINI
            ]);

            // GUNAKAN simpleRsaDecrypt untuk dekripsi
            $transaction = $transactionItem->transaction;
            $transaction->total_amount = $this->simpleRsaEncrypt(
                $transaction->items->sum(function ($item) {
                    return $item->quantity * (float) $this->simpleRsaDecrypt($item->product_price); // <-- PERUBAHAN DI SINI
                })
            );
            $transaction->save();
            
            // Update product stock
            $product->stock = $product->stock + $quantityOld - $dataValidated['quantity'];
            $product->save();

            \DB::commit();

            return redirect()->back()->with('success', 'Transaction item updated successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update transaction item: ' . $e->getMessage());
        }
    }
}
