<?php

namespace App\Http\Controllers;

use App\Http\Services\TransactionsService;
use App\Models\Transactions;
use Illuminate\Http\Request;

class ActiveTransactionsController extends Controller
{
    public function __construct(protected TransactionsService $transactionsService)
    {}

    /* Get data Active Transactions */
    public function index()
    {
        $this->setRule('active-transactions.index');

        // Get all active transactions
        $activeTransactions = $this->transactionsService->getActiveTransactions();

        // Return view with active transactions
        return view('active-transactions.index', compact('activeTransactions'));
    }
}
