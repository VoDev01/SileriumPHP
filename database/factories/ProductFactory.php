<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected static int $id = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if(self::$id == 0)
            self::$id = intval(Product::max('id')) ?? 0;
        self::$id++; 
        return [
            'ulid' => Str::ulid()->toBase32(),
            'id' => self::$id,
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(75),
            'priceRub' => random_int(5000, 100000),
            'available' => 1,
            'subcategory_id' => Subcategory::max('id'),
            'timesPurchased' => 0,
            'productAmount' => random_int(0, 1000000)
        ];
    }
}
