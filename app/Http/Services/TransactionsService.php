<?php

namespace App\Http\Services;

use App\Models\Products;
use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;

class TransactionsService
{

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
                ->where('id', $transactionId)
                ->first();
        }
        return $chart;
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

            // Create a new transaction
            $transaction = Transactions::create([
                'cashier_id' => auth()->id(),
                'total_amount' => $product->price,
                'payment_method' => 'rfid', // Default payment method
                'status' => 'draft', // Default status
            ]);

            // Add selected product to transaction items
            $transaction->items()->create([
                'product_id' => $product->id,
                'quantity' => 1, // Default quantity
                'product_price' => $product->price,
            ]);

            return redirect()->route('transactions.index', $transaction->id)->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }
}
