<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudentAccounts extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'nisn',
        'kelas',
        'status',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /* Relationship with User */
    public function userData()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /* Relationship with RfidCards */
    public function rfidCards()
    {
        return $this->hasMany(RfidCards::class, 'student_id', 'student_id');
    }

    /* Relationship with Topups */
    public function TopUpTransactions()
    {
        return $this->hasMany(TopUpTransactions::class, 'student_id', 'student_id');
    }

    /* Relationship with Transactions */
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'student_id', 'student_id');
    }

}
