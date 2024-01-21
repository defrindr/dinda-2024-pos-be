<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilAplikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_aplikasi',
        'alamat',
        'no_telp',
        'website',
        'logo',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
