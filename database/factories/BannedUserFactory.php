<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BannedUser>
 */
class BannedUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static $timeTypes = ['seconds', 'minutes', 'hours', 'days'];
    public function definition()
    {
        return [
            'reason' => fake()->sentences(10),
            'banTime' => rand(1, 10000),
            'timeType' => self::$timeTypes[rand(0, 3)]
        ];
    }
}
