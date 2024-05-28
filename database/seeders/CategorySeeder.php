<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['id' => 1, 'name' => 'KEBUTUHAN POKOK']);
        Category::create(['id' => 2, 'name' => 'BARANG']);
    }
}
