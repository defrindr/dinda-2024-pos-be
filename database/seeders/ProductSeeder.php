<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = file_get_contents(app_path('/../public/barang.csv'));
        $lines = explode("\n", $csv);
        foreach ($lines as $index => $line) {
            if ($index === 0) continue;
            $columns = explode(",", $line);
            if (count($columns) < 10) continue;

            $fromPayload = [
                'category_id' => ($columns[0] === "KEBUTUHAN POKOK" ? 1 : 2),
                'code' => $columns[1],
                'name' => $columns[2],
                "stock_pack" => $columns[5] * $columns[3],
                "satuan_pack" => $columns[4],
                "per_pack" => $columns[5],
                "harga_pack" => $columns[6],
                "harga_ecer" => $columns[7],
                "jumlah_ecer" => 1, //$columns[8],
                "satuan_ecer" => $columns[9],
                "harga_beli" => $columns[10],
                "description" => "-",
                "date" => date("Y-m-d"),
                "photo" => ''
            ];

            Product::create($fromPayload);
        }
    }
}
