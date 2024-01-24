<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'no_invoice',
        'price',
        'quantity',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
