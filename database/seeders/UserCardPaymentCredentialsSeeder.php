<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserCardPaymentCredentials;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserCardPaymentCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCardPaymentCredentials::factory()->create(['user_id' => User::find(User::max('id'))->ulid]);
    }
}
