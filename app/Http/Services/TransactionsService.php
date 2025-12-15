<?php

namespace App\Http\Services;

use App\Models\Preference;
use App\Models\Products;
use App\Models\RfidCards;
use App\Models\Transactions;
use App\Models\TransactionItems;
use App\Support\SimpleRSA;
use Illuminate\Support\Facades\Storage;

class TransactionsService
{
    // AES Encryption Trait
    use \App\Traits\HasAesEncryption;
    // RSA Encryption Trait
    use \App\Traits\HasRsaEncryption;

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

    /**
     * Get RSA settings
     */
    public function getRsaSettings(): array
    {
        return [
            'n' => Preference::where('name', 'rsa_n')->value('value') ?? '',
            'd' => Preference::where('name', 'rsa_d')->value('value') ?? '',
            'e' => Preference::where('name', 'rsa_e')->value('value') ?? '',
        ];
    }

    /**
     * Update RSA settings
     */
    public function updateRsaSettings(array $data): bool
    {
        try {
            Preference::where('name', 'rsa_n')->update(['value' => $data['n']]);
            Preference::where('name', 'rsa_d')->update(['value' => $data['d']]);
            Preference::where('name', 'rsa_e')->update(['value' => $data['e']]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /* Check if transaction ID is available */
    public function checkTransactionIdAvailability($transactionId = null)
    {
        return $transactionId ? Transactions::find($transactionId) : null;
    }

    /* Get all data product */
    public function getDataAllProducts()
    {
        $products = Products::with('category')
            ->orderBy('name', 'asc')
            ->get();
        return $products;
    }

    /* Get all data added product to chart */
    public function getDataAllAddedProductToChart($transactionId = null)
    {
        $chart = [];
        if ($transactionId) {
            $chart = Transactions::with('cashier', 'student', 'items.product')
                ->where('status', 'draft')
                ->where('id', $transactionId)
                ->first();
        }
        return $chart;
    }

    /** 
     * Get active transaction
     * Status = 'draft'
     */
    public function getActiveTransactions()
    {
        return Transactions::with('cashier', 'student', 'items.product')
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /* Store New Transaction */
    public function storeNewTransaction(array $dataValidated)
    {
        // Product data
        $product = Products::find($dataValidated['product_id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Validate product quantity
        if ($product->stock < 1) {
            return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
        }
        try {
            // Start a transaction
            \DB::beginTransaction();

            // Create a new transaction
            $transaction = Transactions::create([
                'cashier_id' => auth()->id(),
                'total_amount' => $this->simpleRsaEncrypt($product->price),
                'payment_method' => 'rfid', // Default payment method
                'status' => 'draft', // Default status
            ]);

            // Add selected product to transaction items
            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity' => 1, // Default quantity
                'product_price' => $this->simpleRsaEncrypt($product->price),
            ]);

            // Update product stock
            $product->stock -= 1; // Decrease stock by 1
            $product->save();

            // Commit the transaction
            \DB::commit();

            return redirect()->route('transactions.index', $transaction->id)->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    /* Store New Transaction */
    public function storeNewTransaction_old(array $dataValidated)
    {
        // Product data
        $product = Products::find($dataValidated['product_id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Validate product quantity
        if ($product->stock < 1) {
            return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
        }
        try {
            // Start a transaction
            \DB::beginTransaction();

            // Create a new transaction
            $transaction = Transactions::create([
                'cashier_id' => auth()->id(),
                'total_amount' => $this->simpleRsaEncrypt($product->price),
                'payment_method' => 'rfid', // Default payment method
                'status' => 'draft', // Default status
            ]);

            // Add selected product to transaction items
            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity' => 1, // Default quantity
                'product_price' => $this->simpleRsaEncrypt($product->price),
            ]);

            // Update product stock
            $product->stock -= 1; // Decrease stock by 1
            $product->save();

            // Commit the transaction
            \DB::commit();

            return redirect()->route('transactions.index', $transaction->id)->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    /* Store New Item in Same Transaction */
    public function storeNewItemInSameTransaction(array $dataValidated, string $transactionId)
    {
        // Find the transaction
        $transaction = Transactions::find($transactionId);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Validate product
        $product = Products::find($dataValidated['product_id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Validate available product in transaction items
        $existingItem = $transaction->items()->where('product_id', $product->id)->first();
        if ($existingItem) {
            return redirect()->back()->with('error', 'Product already exists in this transaction. You can update the quantity instead.');
        }

        // Validate product stock
        if ($product->stock < 1) {
            return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
        }

        try {
            // Start a transaction
            \DB::beginTransaction();

            // Add new item to the transaction
            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity' => 1, // Default quantity
                'product_price' => $this->simpleRsaEncrypt($product->price),
            ]);

            // Update product stock
            $product->stock -= 1; // Decrease stock by 1
            $product->save();

            // Update the transaction total amount
            $decrypted = (float) $this->simpleRsaDecrypt($transaction->total_amount);
            $productPrice = (float) $product->price;

            $total = $decrypted + $productPrice;

            $transaction->total_amount = $this->simpleRsaEncrypt($total);
            $transaction->save();

            // Commit the transaction
            \DB::commit();

            return redirect()->route('transactions.index', $transactionId)->with('success', 'Item added to transaction successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add item to transaction: ' . $e->getMessage());
        }
    }

    /* Update transaction */
    public function updateTransactionItem(array $dataValidated, string $transactionItemId)
    {
        // Find the transaction item
        $transactionItem = TransactionItems::find($transactionItemId);
        if (!$transactionItem) {
            return redirect()->back()->with('error', 'Transaction item not found.');
        }

        // Validate quantity
        if ($dataValidated['quantity'] < 1) {
            return redirect()->back()->with('error', 'Quantity must be at least 1.');
        }

        // Validate Stock
        $product = $transactionItem->product;
        $quantityOld = $transactionItem->quantity;
        $availableStock = $product->stock + $quantityOld; // Available stock after considering the old quantity
        if ($dataValidated['quantity'] > $availableStock) {
            return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
        }

        try {
            // Start a transaction
            \DB::beginTransaction();

            // Update the transaction item
            $transactionItem->update([
                'quantity' => $dataValidated['quantity'],
                'product_price' => $this->simpleRsaEncrypt($product->price), // Update price if needed
            ]);

            // Update the transaction total amount
            $transaction = $transactionItem->transaction;
            $transaction->total_amount = $this->simpleRsaEncrypt($transaction->items->sum(function ($item) {
                return $item->quantity * (float) $this->simpleRsaDecrypt($item->product_price);
            }));
            $transaction->save();
            
            // Update product stock
            $product->stock = $availableStock - $dataValidated['quantity'];
            $product->save();

            // Commit the transaction
            \DB::commit();

            return redirect()->route('transactions.index', $transactionItem->transaction_id)->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    /* Delete Transaction Item */
    public function deleteTransactionItem(string $transactionItemId)
    {
        // Find the transaction item
        $transactionItem = TransactionItems::find($transactionItemId);
        if (!$transactionItem) {
            return redirect()->back()->with('error', 'Transaction item not found.');
        }
        
        // Start a transaction
        \DB::beginTransaction();
        
        try {
            // Get tranasction data
            $transaction = $transactionItem->transaction;

            // Update product stock before deleting the item
            $product = $transactionItem->product;
            $product->stock += $transactionItem->quantity; // Restore stock
            $product->save();

            // Delete the transaction item
            $transactionItem->delete();

            /**
             * Check if the transaction has one item left.
             * If so, we will delete the transaction.
             * else, we will just update the total amount. 
             */
            if ($transaction->items()->count() === 0) {
                // If no items left, delete the transaction
                $transaction->delete();

                \DB::commit();
                return redirect()->route('transactions.index', ['transactionId' => null])->with('success', 'Transaction deleted successfully.');
            }

            // Update the transaction total amount
            $transaction->total_amount = $this->simpleRsaEncrypt( $transaction->items->sum(function ($item) {
                return $item->quantity * (float) $this->simpleRsaDecrypt($item->product_price);
            }));
            $transaction->save();

            // Commit the transaction
            \DB::commit();
            return redirect()->route('transactions.index', $transaction->id)->with('success', 'Transaction item deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete transaction item: ' . $e->getMessage());
        }
    }

    /* Delete Transaction */
    public function deleteTransaction(string $id)
    {
        // Find the transaction
        $transaction = Transactions::find($id);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        try {
            // Start a transaction
            \DB::beginTransaction();

            // Restore stock for each item in the transaction
            foreach ($transaction->items as $item) {
                $product = $item->product;
                $product->stock += $item->quantity; // Restore stock
                $product->save();
            }

            // Delete the transaction and its items
            $transaction->items()->delete();
            $transaction->delete();

            // Commit the transaction
            \DB::commit();
            return redirect()->route('transactions.index', ['transactionId' => null])->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    /* Pay Transaction */
    public function payTransaction(array $dataValidated, int $transactionId)
    {
        // Define the data for the payment
        $rfidNumber = $dataValidated['rfid_number'] ?? null;
        $rfidPin = $dataValidated['rfid_pin'] ?? null;
        $paymentMethod = $rfidNumber ? 'rfid' : 'cash'; // Determine payment method based on input
        
        // Validate payment method
        if (!in_array($paymentMethod, ['rfid', 'cash'])) {
            return redirect()->back()->with('error', 'Invalid payment method.');
        }

        // Validate RFID payment method
        if ($paymentMethod === 'rfid') {
            // Validate existing RFID number and PIN
            if (!$rfidNumber || !$rfidPin) {
                return redirect()->back()->with('error', 'RFID number and PIN are required for RFID payment.');
            }

            // validate length of RFID
            if (strlen($rfidNumber) !== 10){
                return redirect()->back()->with('error', 'Sorry, please rescan your RFID Card.');
            }

            // validate length of PIN
            if (strlen($rfidPin) !== 6){
                return redirect()->back()->with('error', 'Sorry, please re-enter your PIN.');
            }

            // Check if the RFID card exists
            $existingCards = 0;
            $rfidCards = RfidCards::where('status', 'active')->get();
            foreach ($rfidCards as $card) {
                if ($this->aesDecrypt($card->card_number) === $rfidNumber) {
                    $rfidCard = $card;
                    $existingCards++;
                }
            }

            // If RFID card does not exist
            if ($existingCards == 0) {
                return redirect()->back()->with('error', 'RFID card not found. Please check your card number.');
            }

            // Check if the RFID card is active
            if ($rfidCard->status !== 'active') {
                return redirect()->back()->with('error', 'RFID card is inactive. Please contact support.');
            }
            // Check if the RFID PIN matches
            if ($this->aesDecrypt($rfidCard->card_pin) !== $rfidPin) {
                // Increment failed attempts
                $rfidCard->failed_attempts += 1;

                // Block the RFID card after 3 failed attempts
                if ($rfidCard->failed_attempts >= 3) {
                    $rfidCard->status = 'inactive'; // Block the card
                    $rfidCard->save();

                    return redirect()->back()->with('error', 'RFID PIN is incorrect. Your card has been blocked after 3 failed attempts.');
                }

                $rfidCard->save(); // Save increment
                return redirect()->back()->with('error', 'Invalid RFID PIN. Please try again.');
            }
            // Reset failed attempts if PIN is correct
            $rfidCard->failed_attempts = 0;

            // Check RFID Card Owner (Student ID)
            if (!$rfidCard->studentAccount) {
                return redirect()->back()->with('error', 'RFID card does not belong to any student account.');
            }
        }

        // Find the transaction
        $transaction = Transactions::find($transactionId);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Check if the transaction is already paid
        if ($transaction->status === 'paid') {
            return redirect()->to('/transactions')->with('error', 'Transaction is already paid.');
        }

        try {
            // Start a transaction
            \DB::beginTransaction();

            // If payment method is RFID, update the RFID card status
            if ($paymentMethod === 'rfid') {
                // Deduct the transaction amount from the RFID card balance
                $rfidCard->studentAccount->balance -= (float) $this->simpleRsaDecrypt($transaction->total_amount);

                // Check if the balance is sufficient
                if ($rfidCard->studentAccount->balance < 0) {
                    \DB::rollBack();
                    return redirect()->back()->with('error', 'Insufficient balance on RFID card.');
                }

                // Save the updated RFID card
                $rfidCard->studentAccount->save();
            }

            // Update the transaction status to paid
            $transaction->payment_method = $paymentMethod;
            $transaction->student_id = $rfidCard->student_id;
            $transaction->status = 'paid';
            $transaction->save();

            // Commit the transaction
            \DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaction paid successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to pay transaction: ' . $e->getMessage());
        }
    }
}
