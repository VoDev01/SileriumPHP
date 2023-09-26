<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(75),
            'priceRub' => random_int(5000, 25000),
            'stockAmount' => random_int(10, 100000),
            'available' => 1,
            'subcategory_id' => 1,
            'timesPurchased' => 0
        ];
    }
}
