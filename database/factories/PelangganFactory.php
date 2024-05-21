<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $number = rand(1, 9999);
        $gender = ['L', 'P'];
        $statuses = ['active', 'nonactive'];

        $nik = "";

        for ($char = 0; $char < 16; $char++) {
            $nik .= random_int(0, 9);
        }

        return [
            'code' => 'PLG0000' . $number,
            'nik' => $nik,
            'name' => 'Pelanggan ' . $number,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'gender' => $gender[rand(0, 1)],
            'dob' => fake()->date(),
            'status' => $statuses[rand(0, 1)],
        ];
    }
}
