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
        $students = $datas['students'];
        $isStudentOrParent = $datas['isStudentOrParent'];

        if($isStudentOrParent) {
            return view('report.transactions.index-student-or-parent', compact('transactions'));
        }

        return view('report.transactions.index-cashier', compact('transactions', 'students', 'isStudentOrParent'));
    }

    public function show($studentId)
    {
        $this->setRule('report-transactions.read');

        // Get data
        $transactions = $this->transactionsService->getTransactionByStudentId($studentId);
        $student = $this->transactionsService->getStudentById($studentId);
        return view('report.transactions.show-transaction-by-student-id', compact('transactions', 'student'));
    }

}
