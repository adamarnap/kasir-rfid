<?php

namespace App\Http\Services\Report;

use App\Enums\RoleEnum;
use App\Models\TopUpTransactions;
use Illuminate\Support\Facades\Storage;

class TopupService
{
    /* Get Data All Topups */
    public function getAllTopups()
    {

        // Query to get all topup transactions with related models
        $query = TopUpTransactions::with(['cashier', 'studentAccount'])->orderBy('created_at', 'desc');
        /**
         *  Check user role 
         *  If user role is Student or Parent then Get Student ID from Authenticated User
        */
        $isStudentOrParent = false;
        // If user logged in as Student then get Student ID from User ID, because User ID is the same as Student ID
        if (auth()->user()->hasRole(RoleEnum::STUDENT->value)) {
            $studentId = auth()->user()->id;
            $isStudentOrParent = true;
            $query->where('student_id', $studentId);
        }
        // If user logged in as Parent then get Student ID from Parent relationship
        else if (auth()->user()->hasRole(RoleEnum::PARENT->value)) {
            $studentId = auth()->user()->parent->student_id ?? null;
            $isStudentOrParent = true;
            $query->where('student_id', $studentId);
        }

        // Return the query result
        return [
            'topups' => $query->get(),
            'isStudentOrParent' => $isStudentOrParent,
        ];
    }
}