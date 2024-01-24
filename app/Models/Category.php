<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Category extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scopeSearch(Builder $query, string|null $search)
    {
        $query?->where('name', 'like', "$search%");
    }
}
