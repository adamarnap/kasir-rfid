<?php

namespace App\Http\Services\Report;

use App\Models\TopUpTransactions;
use Illuminate\Support\Facades\Storage;

class TopupService
{
    /* Get Data All Topups */
    public function getAllTopups()
    {
        return TopUpTransactions::with(['cashier', 'studentAccount'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}