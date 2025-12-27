<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->word(),
            'stock_quantity' => fake()->numberBetween(-10000, 10000),
            'image' => fake()->word(),
        ];
    }
}
