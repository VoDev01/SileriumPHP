<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
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
            self::$id = intval(Seller::max('id')) ?? 0;
        self::$id++;
        return [
            'ulid' => Str::ulid()->toBase32(),
            'id' => self::$id,
            'nickname' => fake()->company(),
            'organization_name' => fake('ru_RU')->company(),
            'logo' => "default.png",
            'organization_email' => fake()->companyEmail(),
            'organization_type' => fake('ru_RU')->companyPrefix(),
            'tax_system' => "ОСНО",
            'user_id' => function() { return User::factory()->create()->id; }
        ];
    }
}
