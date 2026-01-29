<?php

namespace App\Http\Controllers;

use App\Http\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('dashboard.read');
        $countAllTransactions = $this->dashboardService->getCountAllTransactions();
        $countDraftTransactions = $this->dashboardService->getCountDraftTransactions();
        $countPaidTransactions = $this->dashboardService->getCountPaidTransactions();
        $countStudents = $this->dashboardService->getCountStudents();
        $countParents = $this->dashboardService->getCountParents();
        $countRfidCards = $this->dashboardService->getCountRfidCards();
        $isStudentOrParent = $this->dashboardService->checkIsStudentOrParent();
        $summaryTransactionsByStudentId = $this->dashboardService->getSummaryTransactionsByStudentId();
        $studentBalanceByStudentId = $this->dashboardService->getStudentBalanceByStudentId();
        $studentInfo = $this->dashboardService->getStudentInfo();
        
        return view('dashboard.index', compact(
            'countAllTransactions',
            'countDraftTransactions',
            'countPaidTransactions',
            'countStudents',
            'countParents',
            'countRfidCards',
            'isStudentOrParent',
            'summaryTransactionsByStudentId',
            'studentBalanceByStudentId',
            'studentInfo'
        ));
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
