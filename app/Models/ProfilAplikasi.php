<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfilAplikasi extends BaseModel
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
        'updated_at' => 'datetime',
    ];

    public static function getRelativePath()
    {
        return 'applogo/';
    }
}
