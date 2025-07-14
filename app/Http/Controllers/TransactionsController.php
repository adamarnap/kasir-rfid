<?php

namespace App\Http\Controllers;

use App\Http\Services\TransactionsService;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __construct(protected TransactionsService $transactionsService)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index($transactionId = null)
    {
        $this->setRule('transactions.read');

        // Check available transaction by transaction id
        $transaction = $this->transactionsService->checkTransactionIdAvailability($transactionId);
        if (!$transaction && $transactionId) {
            return redirect()->to('/transactions')->with('error', 'Transaction not found.');
        }
        
        // Get data
        $products = $this->transactionsService->getDataAllProducts();
        $chart = $this->transactionsService->getDataAllAddedProductToChart($transactionId);
        return view('transactions.index', compact('products', 'chart', 'transactionId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('transactions.store');
        // Validate request
        $dataValidated = $request->validate([
            'product_id' => 'required',
        ]);
        
        // Process the transaction
        return $this->transactionsService->storeNewTransaction($dataValidated);
    }

    /** 
     * Store a newly created item in the same transaction.
     * This method is used to add a new item to an existing transaction.
     */

    public function storeNewItemInSameTransaction($transactionId)
    {
        $this->setRule('transactions.create');
        // Validate request
        $dataValidated = request()->validate([
            'product_id' => 'required',
        ]);
        
        // Process the transaction
        return $this->transactionsService->storeNewItemInSameTransaction($dataValidated, $transactionId);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateQuantity(Request $request, string $transactionItemId)
    {
        $this->setRule('transactions.update');
        // Validate request
        $dataValidated = $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);
        // Update process
        return $this->transactionsService->updateTransactionItem($dataValidated, $transactionItemId);
    }

    /**
     * Remove the specified item from the transaction.
     */
    public function itemDestroy(string $transactionItemId)
    {
        $this->setRule('transactions.delete');
        // Delete the transaction item
        return $this->transactionsService->deleteTransactionItem($transactionItemId);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->setRule('transactions.delete');
        // Delete the transaction
        return $this->transactionsService->deleteTransaction($id);
    }

    /**
     * Pay for the transaction.
     */
    public function pay(Request $request, string $transactionId)
    {
        $this->setRule('transactions.update');
        // Validate request
        $dataValidated = $request->validate([
            'payment_method' => 'required|in:cash,rfid',
            // if payment method is rfid, then rfid_number and rfid_pin are required
            'rfid_number' => 'required_if:payment_method,rfid|nullable|string|max:10',
            'rfid_pin' => 'required_if:payment_method,rfid|nullable|string|max:6',
        ]);

        // Process the payment
        return $this->transactionsService->payTransaction($dataValidated, $transactionId);
    }

    /** 
     * Get active transactions.
     * This method retrieves all active transactions.
     * transactions status = 'draft'
     */
    public function active()
    {
        $this->setRule('transactions.read');
        // Get active transactions
        $activeTransactions = $this->transactionsService->getActiveTransactions();
        return view('transactions.active', compact('activeTransactions'));
    }
}
