<?php

namespace App\Http\Services;

use App\Models\ParentStudent;
use App\Models\RfidCards;
use App\Models\StudentAccounts;
use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;


class DashboardService
{

    /**
     * Get the count of all transactions.
     *
     * @return int
     */
    public function getCountAllTransactions(): int
    {
        // Assuming you have a Transaction model and it has a method to count all transactions
        return Transactions::count();
    }

    /**
     * Get the count of draft transactions.
     *
     * @return int
     */
    public function getCountDraftTransactions(): int
    {
        // Assuming you have a Transaction model and it has a method to count draft transactions
        return Transactions::where('status', 'draft')->count();
    }

    /**
     * Get the count of transactions that are paid.
     *
     * @return int
     */

    public function getCountPaidTransactions(): int
    {
        // Assuming you have a Transaction model and it has a method to count paid transactions
        return Transactions::where('status', 'paid')->count();
    }

    /* Get Count Students */
    public function getCountStudents(): int
    {
        // Assuming you have a Student model and it has a method to count all students
        return StudentAccounts::count();
    }

    /* Get Count Parents */
    public function getCountParents(): int
    {
        // Assuming you have a Parent model and it has a method to count all parents
        return ParentStudent::count();
    }
    
    /* Get Count RFID Cards */
    public function getCountRfidCards(): int
    {
        // Assuming you have a RfidCard model and it has a method to count all RFID cards
        return RfidCards::count();
    }

}