<?php

namespace App\Models;

use App\Models\User;
use App\Models\StudentAccounts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'student_id',
        'total_amount',
        'payment_method',
        'status',
    ];

    // Cashier Relationship
    public function cashier(){
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Student Relationship
    public function student(){
        return $this->belongsTo(StudentAccounts::class, 'student_id', 'student_id');
    }

    // Transaction Items Relationship
    public function items(){
        return $this->hasMany(TransactionItems::class, 'transaction_id');
    }
}
