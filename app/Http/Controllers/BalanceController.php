<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\BalanceService;

class BalanceController extends Controller
{
    public function __construct(protected BalanceService $balanceService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('balance.read');
        // Get data
        return view('balance.index');
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

    // Get Data Balance RFID by RFID Number - via (Ajax)
    public function show(string $rfidNumber)
    {
        $this->setRule('balance.show');
        // Get data
        return $this->balanceService->getBalanceByRfidNumber($rfidNumber);
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
