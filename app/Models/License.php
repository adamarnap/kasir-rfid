<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'license_key', 'expires_at', 'is_active'
    ];

    // Agar expires_at jadi Carbon instance
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
