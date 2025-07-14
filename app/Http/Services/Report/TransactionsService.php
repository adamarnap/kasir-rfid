<?php

namespace App\Http\Services\Report;

use App\Enums\RoleEnum;
use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;

class TransactionsService
{

    /* Get All Transactions */
    public function getAllTransactions()
    {
        /**
         *  Check user role 
         *  If user role is Student or Parent then Get Student ID from Authenticated User
        */
        $isStudentOrParent = false;
        $query = Transactions::with(['cashier', 'student', 'items'])->orderBy('created_at', 'desc');

        // If user logged in as Student then get Student ID from User ID, because User ID is the same as Student ID
        if (auth()->user()->hasRole(RoleEnum::STUDENT->value)) {
            $studentId = auth()->user()->id;

            // Set flag isStudentOrParent to true
            $isStudentOrParent = true;

            // Filter transactions by Student ID
            $query->where('student_id', $studentId);
        }
        // If user logged in as Parent then get Student ID from Parent relationship
        else if (auth()->user()->hasRole(RoleEnum::PARENT->value)) {
            $studentId = auth()->user()->parent->student_id ?? null;

            // Set flag isStudentOrParent to true
            $isStudentOrParent = true;

            // Filter transactions by Student ID
            $query->where('student_id', $studentId);
        }

        return [
            'transactions' => $query->get(),
            'isStudentOrParent' => $isStudentOrParent,
        ];

        
    }

}