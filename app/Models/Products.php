<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasSimpleRsaEncryption;

class Products extends Model
{
    use HasFactory, HasSimpleRsaEncryption;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'stock',
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

    /**
     * Get decrypted price
     */
    public function getPriceAttribute($value)
    {
        return $this->simpleRsaDecrypt($value);
    }

    /**
     * Set encrypted price
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $this->simpleRsaEncrypt($value);
    }

    /* Category Relationship */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
