<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RfidCards extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'student_id',
        'card_number',
        'card_pin',
        'status',
        'description',
        'created_by',
    ];

    /* Booted for created_by */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
    }

    /**
     * Relationship with studentAccounts
     */
    public function studentAccount()
    {
        return $this->belongsTo(StudentAccounts::class, 'student_id', 'student_id');
    }
}
