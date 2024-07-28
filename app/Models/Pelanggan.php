<?php

namespace App\Models;

use App\Traits\EncryptPersonalData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends BaseModel
{
    use EncryptPersonalData, HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'nik',
        'name',
        'phone',
        'address',
        'gender',
        'dob',
        'status',
    ];

    protected $hidden = ['salt'];

    protected $encryptable = [
        'code',
        'nik',
        'name',
        'phone',
        'address',
        'gender',
        'dob',
        'status',
    ];

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
                    ->orWhere('name', 'like', "%$search%");
            });
        }
    }
}
