<?php

namespace App\Models;

use App\Traits\EncryptPersonalData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends BaseModel
{
    use EncryptPersonalData, HasFactory, SoftDeletes;

    protected $fillable = [
        'kasir_id',
        'customer_id',
        'invoice',
        'date',
        'total_price',
        'total_pay',
        'total_return',
        'created_at',
    ];

    protected $hidden = ['salt'];

    protected $encryptable = [
        'invoice',
        'date',
        // 'total_price',
        // 'total_pay',
        // 'total_return',
    ];

    protected $saltcolumn = 'salt';


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSearch(Builder $builder, ?string $query)
    {
        $builder->where(function ($builder) use ($query) {
            $builder->where('invoice', 'like', "%$query%")
                ->orWhere('date', 'like', "%$query%");
        });
    }

    /**
     * relation to table transaction detail
     */
    public function items()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }
}
