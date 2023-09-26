<?php

namespace Database\Factories;

use App\Models\ProductSpecifications;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductSpecificationsFactory extends Factory
{
    protected $model = ProductSpecifications::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence(2),
            'specification' => fake()->sentence(5)
        ];
    }
}
