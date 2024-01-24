<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'code'     => '999999999999',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'email'    => 'superadmin@mail.com',
            'name'     => 'John Doe',
            'phone'    => '628560162537',
            'photo'    => 'default.png',
            'role'    => User::LEVEL_ADMIN
        ]);
    }
}
