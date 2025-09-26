<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserCardPaymentCredentials>
 */
class UserCardPaymentCredentialsFactory extends Factory
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
            'number' => rand(1111, 9999) . ' ' . rand(1111, 9999) . ' ' . rand(1111, 9999) . ' ' . rand(1111, 9999),
            'expiry' => rand(1, 12) . '/' . rand(1, 25),
            'ccv' => rand(100, 999),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }
}
