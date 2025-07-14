<?php

namespace App\Http\Services\Report;

use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;

class TransactionsService
{

    /* Get All Transactions */
    public function getAllTransactions()
    {
        return Transactions::with(['cashier', 'student', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

}