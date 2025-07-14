<?php

namespace App\Http\Services\Master;

use App\Enums\RoleEnum;
use App\Models\StudentAccounts;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class StudentsService
{
    /* Get Data All Students */
    public function getDataAllStudents()
    {
        return StudentAccounts::with(['userData'])
            ->orderBy('created_at', 'desc')
            ->where('status', 'active')
            ->get();
    }

    /* Store Process */
    public function store($dataValidated)
    {
        try{
            // Start transaction
            \DB::beginTransaction();
            // Create account
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
            $user->assignRole(RoleEnum::STUDENT->value);
            
            // Create student account
            $studentAccount = StudentAccounts::create([
                'student_id' => $user->id,
                'nisn' => $dataValidated['nisn'],
                'kelas' => $dataValidated['kelas'],
                'status' => $dataValidated['status'],
                'balance' => 0, // Default balance
            ]);
            // Commit transaction
            \DB::commit();
            // Return success response
            return redirect()->route('master.students.index')->with('success', 'Student data stored successfully.');
        }catch(\Exception $e){
            \DB::rollback();
            // Handle exception, log error, etc.
            return back()->withErrors(['error' => 'Failed to store student data: ' . $e->getMessage()]);
        }
    }

    /* Process Udpate Data */
    public function update($studentsId, $dataValidated)
    {
        try {
            // Start transaction
            \DB::beginTransaction();
            // Find student account
            $studentAccount = StudentAccounts::findOrFail($studentsId);
            // Update user data
            $user = $studentAccount->userData;
            $user->update([
                'name' => $dataValidated['name'],
                'email' => $dataValidated['email'],
                'jenis_kelamin' => $dataValidated['jenis_kelamin'],
                'telepon' => $dataValidated['telepon'] ?? null,
                'alamat' => $dataValidated['alamat'] ?? null,
            ]);
            // Update student account data
            $studentAccount->update([
                'nisn' => $dataValidated['nisn'],
                'kelas' => $dataValidated['kelas'],
                'status' => $dataValidated['status'],
            ]);
            // Commit transaction
            \DB::commit();
            return redirect()->route('master.students.index')->with('success', 'Student data updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Failed to update student data: ' . $e->getMessage()]);
        }
    }

    /* Delete Student */
    public function destroy($studentsId)
    {
        try {
            // Start transaction
            \DB::beginTransaction();
            // Find student account
            $studentAccount = StudentAccounts::findOrFail($studentsId);
            // Delete user data
            $user = $studentAccount->userData;
            $user->delete();
            // Delete student account
            $studentAccount->delete();
            // Commit transaction
            \DB::commit();
            return redirect()->route('master.students.index')->with('success', 'Student data deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete student data: ' . $e->getMessage()]);
        }   
    }
}

