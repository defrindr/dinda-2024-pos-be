<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    private static $order = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();

        self::$order += 1;

        return [
            'code' => 'PRD'.sprintf('%03d', self::$order),
            'name' => fake()->name(),
            'unit' => fake()->word(),
            'stock' => fake()->numberBetween(1, 200),
            'price_buy' => fake()->numberBetween(1000, 900000),
            'price_sell' => fake()->numberBetween(1000, 900000),
            'description' => fake()->paragraphs(3, true),
            'date' => fake()->date(),
            'photo' => '',
            'category_id' => $category->id,
            'per_pack' => fake()->numberBetween(1, 12),
            'per_item' => 1,
            'unit_item' => 'Biji',
            'price_sell_item' => fake()->numberBetween(1000, 900000),
        ];
    }
}
