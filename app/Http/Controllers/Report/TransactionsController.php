<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Services\Report\TransactionsService;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __construct(protected TransactionsService $transactionsService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('report-transactions.read');
        // Get data
        $transactions = $this->transactionsService->getAllTransactions();
        
        return view('report.transactions.index', compact('transactions'));
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
        //
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
