<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /* Boot */
    // protected static function booted()
    // {
    //     static::creating(function ($model) {
    //         // Added Created By When Creating
    //         $model->created_by = auth()->user()->id;
    //     });
    // }
}
