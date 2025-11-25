<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
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
            'name' => Crypt::encrypt(fake()->firstName()),
            'surname' => Crypt::encrypt(fake()->lastName()),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('1122334455', ['rounds' => 12]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'birthDate' => fake()->dateTime(),
            'country' => Crypt::encrypt(fake()->country()),
            'city' => Crypt::encrypt(fake()->city()),
            'homeAdress' => Crypt::encrypt(fake()->streetAddress()),
            'phone' => Crypt::encrypt(fake()->phoneNumber()),
            'profilePicture' => '/media/images/pfp/default_user.png',
            'remember_token' => Str::random(10),
            'token' => Str::random(rand(16, 64)),
            'expiresIn' => Carbon::now()->addMinutes(env('APP_USER_REFRESH'))->format('Y-m-d H:i:s'),
            'email_verified_at' => null
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
