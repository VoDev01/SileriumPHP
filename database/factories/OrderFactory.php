<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use App\Models\User;
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
            'totalPrice' => mt_rand(100, 1000000),
            'orderDate' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'orderAdress' => fake()->address(),
            'orderStatus' => 'PENDING',
            'deleted_at' => null,
            'updated_at' => null,
            'user_id' => User::max('id')
        ];
    }
}
