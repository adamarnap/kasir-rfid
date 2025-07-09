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
        $this->setRule('transactions.index');
        // Get data
        $products = $this->transactionsService->getDataAllProducts();
        $chart = $this->transactionsService->getDataAllAddedProductToChart($transactionId);
        // dd($chart);
        return view('transactions.index', compact('products', 'chart', 'transactionId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
