<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiUser>
 */
class ApiUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => Str::ulid()->toBase32(),
            'name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '1122334455',
            'created_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'phone' => fake()->phoneNumber(),
            //'email_verified_at' => null
        ];
    }
}
