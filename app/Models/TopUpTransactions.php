<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopUpTransactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'cashier_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship with StudentAccounts
     */
    public function studentAccount()
    {
        return $this->belongsTo(StudentAccounts::class, 'student_id', 'student_id');
    }

    /**
     * Relationship with User (Cashier)
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id', 'id');
    }
}
