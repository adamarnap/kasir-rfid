<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];


    /* Category Relationship */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
