<?php

namespace App\Http\Services\Report;

use App\Enums\RoleEnum;
use App\Models\StudentAccounts;
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
        /* Role Admin Or Cahsier Or Developer */
        if(auth()->user()->hasRole(RoleEnum::ADMIN->value) || auth()->user()->hasRole(RoleEnum::CASHIER->value) || auth()->user()->hasRole(RoleEnum::DEVELOPER->value)) {
            $query = StudentAccounts::with(['userData', 'transactions.cashier', 'transactions.student', 'transactions.items'])->orderBy('created_at', 'desc');
            // dd($query->get());
            // If user is Admin or Operator, get all transactions
            return [
                'students' => $query->get(),
                'transactions' => [],
                'isStudentOrParent' => false,
            ];
        }

        /* Role Student Or Parent */
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
            'students' => [],
            'transactions' => $query->get(),
            'isStudentOrParent' => $isStudentOrParent,
        ];
    }

    /* Get data transactions by student Id */
    public function getTransactionByStudentId($studentId)
    {
        return Transactions::with(['cashier', 'student', 'items'])
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /* Get data student by student ID */
    public function getStudentById($studentId)
    {
        return StudentAccounts::with(['userData'])
            ->where('student_id', $studentId)
            ->first();
    }

}