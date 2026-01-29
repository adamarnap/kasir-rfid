<?php

namespace App\Http\Services;

use App\Enums\RoleEnum;
use App\Models\Preference;
use App\Models\RfidCards;
use App\Models\Transactions;
use App\Models\ParentStudent;
use App\Models\StudentAccounts;
use App\Support\SimpleRSA;
use Illuminate\Support\Facades\Storage;


class DashboardService
{

    // AES Encryption Trait
    use \App\Traits\HasAesEncryption;
    // RSA Encryption Trait
    use \App\Traits\HasRsaEncryption;

    /**
     * Get SimpleRSA instance from database keys
     */
    protected function getSimpleRSA(): SimpleRSA
    {
        $n = Preference::where('name', 'rsa_n')->value('value');
        $d = Preference::where('name', 'rsa_d')->value('value');
        $e = Preference::where('name', 'rsa_e')->value('value');

        if (!$n || !$d || !$e) {
            throw new \RuntimeException('RSA keys not found in database. Please configure encryption settings.');
        }

        return new SimpleRSA($n, $d, $e);
    }

    /**
     * Encrypt using SimpleRSA
     */
    protected function simpleRsaEncrypt(string $plaintext): string
    {
        return $this->getSimpleRSA()->encrypt($plaintext);
    }

    /**
     * Decrypt using SimpleRSA
     */
    protected function simpleRsaDecrypt(string $ciphertext): string
    {
        return $this->getSimpleRSA()->decrypt($ciphertext);
    }

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

    /* Get User has Logged is Student or Parent */
    public function checkIsStudentOrParent(): bool
    {
        // Assuming you have a way to check if the user is a student or parent
        return auth()->user()->hasRole([RoleEnum::STUDENT->value, RoleEnum::PARENT->value]);
    }

    /**
     * Get summary transactions by student ID.
     *
     * @return array
     */
    public function getSummaryTransactionsByStudentId(): array
    {
        $isStudentOrParent = $this->checkIsStudentOrParent();

        if ($isStudentOrParent) {
            // If user logged in as Student then get Student ID from User ID, because User ID is the same as Student ID
            if (auth()->user()->hasRole(RoleEnum::STUDENT->value)) {
                $studentId = auth()->user()->id;
            }
            // If user logged in as Parent then get Student ID from Parent relationship
            else if (auth()->user()->hasRole(RoleEnum::PARENT->value)) {
                $studentId = auth()->user()->parent->student_id ?? null;
            }

            // Fetch the summary transactions for the student
            $summaryTransactionsByStudentId = Transactions::where('student_id', $studentId)->get();
            
            // Decrypt the total amount for each transaction
            $totalAmountTransactions = 0;
            $countTransactions = $summaryTransactionsByStudentId->count();
            foreach($summaryTransactionsByStudentId as $transaction) {
                // Decrypt the total amount for each transaction using SimpleRSA
                $totalAmountTransactions += (float) $this->simpleRsaDecrypt($transaction->total_amount);
            }

            return [
                'total_amount' => $totalAmountTransactions,
                'count_transactions' => $countTransactions,
            ];
        }

        return [];
    }

    /**
     * Get student balance by student ID.
     *
     * @return array
     */
    public function getStudentBalanceByStudentId(): array
    {
        $isStudentOrParent = $this->checkIsStudentOrParent();

        if ($isStudentOrParent) {
            // If user logged in as Student then get Student ID from User ID, because User ID is the same as Student ID
            if (auth()->user()->hasRole(RoleEnum::STUDENT->value)) {
                $studentId = auth()->user()->id;
            }
            // If user logged in as Parent then get Student ID from Parent relationship
            else if (auth()->user()->hasRole(RoleEnum::PARENT->value)) {
                $studentId = auth()->user()->parent->student_id ?? null;
            }

            // Fetch the student balance for the student
            return StudentAccounts::where('student_id', $studentId)
                ->select('balance')
                ->first()
                ->toArray();
        }
        return [];
    }

    /**
     * Get student info by student ID for Student and Parent roles.
     *
     * @return \App\Models\StudentAccounts|null
     */
    public function getStudentInfo()
    {
        $isStudentOrParent = $this->checkIsStudentOrParent();

        if ($isStudentOrParent) {
            // If user logged in as Student then get Student ID from User ID
            if (auth()->user()->hasRole(RoleEnum::STUDENT->value)) {
                $studentId = auth()->user()->id;
            }
            // If user logged in as Parent then get Student ID from Parent relationship
            else if (auth()->user()->hasRole(RoleEnum::PARENT->value)) {
                $studentId = auth()->user()->parent->student_id ?? null;
            }

            // Fetch the student info with user data, rfid cards, and transactions
            return StudentAccounts::with([
                'userData',
                'rfidCards',
                'transactions' => function($query) {
                    $query->latest()->take(5);
                },
                'TopUpTransactions' => function($query) {
                    $query->latest()->take(5);
                }
            ])->where('student_id', $studentId)->first();
        }
        
        return null;
    }
}