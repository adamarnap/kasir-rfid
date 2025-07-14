<?php

namespace App\Http\Services\Master;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\ParentStudent;
use App\Models\StudentAccounts;
use Illuminate\Support\Facades\Storage;

class ParentsService
{
    // Get all parents data
    public function getDataAllParents()
    {
        $data = ParentStudent::with(['userData', 'studentAccount.userData'])
                ->whereHas('studentAccount', function ($query) {
                    $query->where('status', 'active');
                })
                ->get();
        
        return $data; 
    }

    // Get all students data
    public function getDataAllStudents()
    {
        return StudentAccounts::with('userData')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->sortBy(fn ($item) => $item->userData->name ?? '') // sort di collection
            ->values(); // reset index
    }

    // Store new parent data
    public function store($dataValidated){
        try {
            // Start transaction
            \DB::beginTransaction();

            // Create parent user
            $user = User::create([
                'name' => $dataValidated['name'],
                'email' => $dataValidated['email'],
                'password' => bcrypt($dataValidated['password']),
                'jenis_kelamin' => $dataValidated['jenis_kelamin'],
                'telepon' => $dataValidated['telepon'] ?? null,
                'alamat' => $dataValidated['alamat'] ?? null,
                'email_verified_at' => now(),
            ]);

            // Asign Role
            $user->assignRole(RoleEnum::PARENT->value);

            // Create parent-student relationship
            ParentStudent::create([
                'parent_id' => $user->id,
                'student_id' => $dataValidated['student_id'],
                'relationship' => $dataValidated['relationship'],
            ]);

            // Commit transaction
            \DB::commit();
            return redirect()->route('master.parents.index')->with('success', 'Parent data stored successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'Failed to store parent data: ' . $e->getMessage()]);
        }
    }

    // Update parent data
    public function update($parentId, $dataValidated)
    {
        try {
            // Start transaction
            \DB::beginTransaction();
            // Find parent-student relationship
            $parentStudent = ParentStudent::findOrFail($parentId);
            // Update user data
            $user = $parentStudent->userData;
            $user->update([
                'name' => $dataValidated['name'],
                'email' => $dataValidated['email'],
                'jenis_kelamin' => $dataValidated['jenis_kelamin'],
                'telepon' => $dataValidated['telepon'] ?? null,
                'alamat' => $dataValidated['alamat'] ?? null,
            ]);
            // Update parent-student relationship
            $parentStudent->update([
                'relationship' => $dataValidated['relationship'],
                'student_id' => $dataValidated['student_id'],
            ]);
            // Commit transaction
            \DB::commit();
            // Return success response  
            return redirect()->route('master.parents.index')->with('success', 'Parent data updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Failed to update parent data: ' . $e->getMessage()]);
        }   
    }

    // Delete parent data
    public function destroy($parentId)
    {
        try {
            // Start transaction
            \DB::beginTransaction();
            // Find parent-student relationship
            $parentStudent = ParentStudent::findOrFail($parentId);
            // Delete parent-student relationship
            $parentStudent->delete();
            // Delete user data
            $user = $parentStudent->userData;
            $user->delete();
            // Commit transaction
            \DB::commit();
            // Return success response
            return redirect()->route('master.parents.index')->with('success', 'Parent data deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete parent data: ' . $e->getMessage()]);
        }  
    }
}