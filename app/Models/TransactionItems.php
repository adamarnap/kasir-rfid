<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItems extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'product_price',
    ];

    // Transaction Relationship
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    // Product Relationship
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
