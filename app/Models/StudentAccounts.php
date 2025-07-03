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


}
