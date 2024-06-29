<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'satuan',
        'transaction_id',
        'price',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * relation to table product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
