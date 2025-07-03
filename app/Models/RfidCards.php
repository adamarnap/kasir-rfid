<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RfidCards extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'student_id',
        'rfid_code',
        'is_active',
    ];

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
