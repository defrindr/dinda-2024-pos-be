<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "category_id",
        "code",
        "name",
        "stock_pack",
        "satuan_pack",
        "per_pack",
        "harga_pack",
        "harga_ecer",
        "jumlah_ecer",
        "satuan_ecer",
        "harga_beli",
        "description",
        "date",
        "photo",
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSearch(Builder $query, ?string $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('code', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('satuan_pack', 'like', "%$search%")
                ->orWhere('satuan_ecer', 'like', "%$search%");
        });
    }

    public static function getRelativePath()
    {
        return 'products/';
    }
}
