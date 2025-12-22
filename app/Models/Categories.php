<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSimpleRsaEncryption;

class Categories extends Model
{
    use HasFactory, HasSimpleRsaEncryption;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get decrypted name
     */
    public function getNameAttribute($value)
    {
        return $this->simpleRsaDecrypt($value);
    }

    /**
     * Set encrypted name
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->simpleRsaEncrypt($value);
    }

    /**
     * Get decrypted description
     */
    public function getDescriptionAttribute($value)
    {
        return $this->simpleRsaDecrypt($value);
    }

    /**
     * Set encrypted description
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $this->simpleRsaEncrypt($value);
    }

    /* Boot */
    // protected static function booted()
    // {
    //     static::creating(function ($model) {
    //         // Added Created By When Creating
    //         $model->created_by = auth()->user()->id;
    //     });
    // }
}
