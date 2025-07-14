<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Services\Report\TopupService;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function __construct(protected TopupService $topupService)
    {
        // Constructor logic if needed
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('report-topup.read');
        // Get data
        $datas = $this->topupService->getAllTopups();
        $topups = $datas['topups'];
        $isStudentOrParent = $datas['isStudentOrParent'];
        return view('report.topup.index', compact('topups', 'isStudentOrParent'));

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
