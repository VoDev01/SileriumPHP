<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            self::$id = intval(User::max('id')) ?? 0;
        self::$id++; 
        return [
            'ulid' => Str::ulid()->toBase32(),
            'id' => self::$id,
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('1122334455'),
            'created_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'birthDate' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'homeAdress' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'profilePicture' => 'images/pfp/default_user.png',
            'remember_token' => Str::random(10)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
