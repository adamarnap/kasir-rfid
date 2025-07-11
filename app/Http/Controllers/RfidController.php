<?php

namespace App\Http\Controllers;

use App\Http\Services\RfidService;
use Illuminate\Http\Request;

class RfidController extends Controller
{

    public function __construct(protected RfidService $rfidService)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('rfid.read');
        // Get data
        $cards = $this->rfidService->getAllRfidCards();
        $students = $this->rfidService->getAllStudents();
        return view('rfid.index', compact('cards', 'students'));
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
        $this->setRule('rfid.create');
        // Validate request
        $request->validate([
            'student_id' => 'required|exists:student_accounts,student_id',
            'rfid_number' => 'required|numeric|digits:10',
            'rfid_pin' => 'required|numeric|digits:6',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:255',
        ]);

        // Create RFID card
        return $this->rfidService->createRfidCard($request->all());
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
        $this->setRule('rfid.update');
        // Validate request
        $request->validate([
            'rfid_pin' => 'nullable|numeric|digits:6',
            'rfid_number' => 'nullable|numeric|digits:10',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:255',
        ]);

        // Update RFID card
        return $this->rfidService->updateRfidCard($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
