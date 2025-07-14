<?php

namespace App\Http\Controllers\Report;

use App\Enums\RoleEnum;
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
        $datas = $this->transactionsService->getAllTransactions();

        $transactions = $datas['transactions'];
        $isStudentOrParent = $datas['isStudentOrParent'];
        // dd($transactions, $isStudentOrParent);

        return view('report.transactions.index', compact('transactions', 'isStudentOrParent'));
    }

}
