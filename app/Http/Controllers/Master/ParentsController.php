<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\Master\ParentsService;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    public function __construct(protected ParentsService $parentService)
    {
        // You can set any middleware or services here if needed
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('parents.read');
        $parents = $this->parentService->getDataAllParents();
        $students = $this->parentService->getDataAllStudents();
        return view('master.parents.index', compact('parents', 'students'));
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
        $this->setRule('parents.create');
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'jenis_kelamin' => 'required',
            'telepon' => 'nullable|digits_between:1,15',
            'alamat' => 'nullable|string|max:255',
            'relationship' => 'required|in:father,mother,guardian', // Assuming these are the valid options
            'student_id' => 'required|exists:student_accounts,student_id',
            'password' => 'required',
        ]);
        // Call the service to handle the creation logic
        return $this->parentService->store($validatedData);
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
    public function update(Request $request, string $parentId)
    {
        $this->setRule('parents.update');
        //
        $userId = $request->input('user_id');
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'jenis_kelamin' => 'required',
            'telepon' => 'nullable|digits_between:1,15',
            'alamat' => 'nullable|string|max:255',
            'relationship' => 'required|in:father,mother,guardian',
            'student_id' => 'required|exists:student_accounts,student_id',
        ]);
        
        // Call the service to handle the update logic
        return $this->parentService->update($parentId, $validatedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $parentId)
    {
        $this->setRule('parents.delete');
        // Call the service to handle the deletion logic
        return $this->parentService->destroy($parentId);
    }
}
