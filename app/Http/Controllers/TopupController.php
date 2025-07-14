<?php

namespace App\Http\Controllers;

use App\Http\Services\TopupService;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function __construct(protected TopupService $topupService)
    {
        // Initialize any required services or dependencies
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('topup.read');
        // Get data
        return view('topup.index');
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
        $this->setRule('topup.create');
        // Validate request
        $request->validate([
            'rfid_number' => 'required|numeric|digits:10',
            'amount' => 'required|numeric|min:1000',
        ]);

        // Create Top Up
        return $this->topupService->createTopup($request->all());
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
