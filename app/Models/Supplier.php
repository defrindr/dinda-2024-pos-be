<?php

namespace App\Models;

use App\Traits\EncryptPersonalData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends BaseModel
{
    use EncryptPersonalData, HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'status',
    ];

    protected $hidden = ['salt'];

    protected $encryptable = ['name', 'phone', 'address'];

    protected $saltcolumn = 'salt';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSearch(Builder $query, ?string $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            });
        }
    }
}
