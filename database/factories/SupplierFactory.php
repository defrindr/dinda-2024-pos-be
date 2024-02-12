<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = rand(1, 9999);
        $statuses = ['active', 'nonactive'];

        return [
            'code' => 'SPL00000'.$code,
            'name' => 'Supplier '.$code,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'status' => $statuses[rand(0, 1)],
        ];
    }
}
