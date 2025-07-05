<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ParentRelationshipEnum;

class ParentStudent extends Model
{
    use HasFactory;

    protected $table = 'parent_student';

    protected $fillable = [
        'parent_id',
        'student_id',
    ];

    protected $casts = [
        'relationship' => ParentRelationshipEnum::class,
    ];

    // Parent of the student
    public function userData()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Student of the parent
    public function studentAccount()
    {
        return $this->belongsTo(StudentAccounts::class, 'student_id', 'student_id');
    }
}
