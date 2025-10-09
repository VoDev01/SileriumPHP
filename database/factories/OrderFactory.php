<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ulid' => Str::ulid()->toBase32(),
            'created_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'address' => fake()->streetAddress(),
            'status' => 'PENDING',
            'deleted_at' => null,
            'updated_at' => null,
            'totalPrice' => (double)rand(1000, 10000000)
        ];
    }
}
