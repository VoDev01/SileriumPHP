<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
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
            self::$id = intval(Review::max('id')) ?? 0;
        self::$id++; 
        return [
            'ulid' => Str::ulid()->toBase32(),
            'id' => self::$id,
            'title' => fake()->words(3, true),
            'pros' => fake()->sentence(),
            'cons' => fake()->sentence(),
            'comment' => fake()->sentence(),
            'rating' => rand(1, 5),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'product_id' => Product::max('id'),
            'user_id' => User::max('id')
        ];
    }
}
