<?php

namespace Database\Seeders;

use App\Models\ProfilAplikasi;
use Illuminate\Database\Seeder;

class ProfilAplikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfilAplikasi::create([
            'nama_aplikasi' => 'POS Dinda',
            'alamat' => 'Ds. Mojoagung Kab. Jombang',
            'no_telp' => '627361526345',
            'website' => 'http://localhost',
            'logo' => '',
        ]);
    }
}
