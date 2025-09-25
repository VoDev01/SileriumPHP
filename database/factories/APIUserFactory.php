<?php

namespace Database\Factories;

use App\Models\APIUser;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiUser>
 */
class APIUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'api_key' => Str::random(16),
            'secret' => Str::random(32),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }

    protected $model = APIUser::class;
}
