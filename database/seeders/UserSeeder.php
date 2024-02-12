<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'code' => '0000001',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'email' => 'superadmin@mail.com',
            'name' => 'Admin',
            'phone' => '628560162537',
            'photo' => 'default.png',
            'role' => User::LEVEL_ADMIN,
        ]);
        User::create([
            'code' => '1000001',
            'username' => 'kasir',
            'password' => bcrypt('password'),
            'email' => 'kasir@mail.com',
            'name' => 'Kasir',
            'phone' => '628160662541',
            'photo' => 'default.png',
            'role' => User::LEVEL_KASIR,
        ]);
        User::create([
            'code' => '2000001',
            'username' => 'manager',
            'password' => bcrypt('password'),
            'email' => 'manager@mail.com',
            'name' => 'Manager',
            'phone' => '628160662542',
            'photo' => 'default.png',
            'role' => User::LEVEL_MANAGER,
        ]);
    }
}
