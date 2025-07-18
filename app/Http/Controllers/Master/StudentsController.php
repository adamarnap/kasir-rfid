<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\Master\StudentsService;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function __construct(protected StudentsService $studentsService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('students.read');
        // --
        $students = $this->studentsService->getDataAllStudents();
        return view('master.students.index', compact('students'));
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
        $this->setRule('students.create');
        // Validate data
        $dataValidated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:l,p',
            'alamat' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        return $this->studentsService->store($dataValidated);
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
    public function update(Request $request, string $studentId)
    {
        $this->setRule('students.update');
        $userId = $request->input('user_id');
        // Validate data
        $dataValidated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:l,p',
            'alamat' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Process the update logic here, e.g., using a service class
        return $this->studentsService->update($studentId, $dataValidated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $studentId)
    {
        $this->setRule('students.delete');
        // Process the delete logic here, e.g., using a service class
        return $this->studentsService->destroy($studentId);
    }
}
