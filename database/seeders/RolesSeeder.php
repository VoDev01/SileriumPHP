<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role' => 'user'
            ],
            [
                'role' => 'api_user'
            ],
            [
                'role' => 'admin'
            ],
            [
                'role' => 'moderator'
            ],
            [
                'role' => 'seller'
            ]
        ]);
        DB::table('users_roles')->insert([
            [
                'user_id' => 1,
                'role_id' => 1
            ],
            [
                'user_id' => 2,
                'role_id' => 1
            ],
            [
                'user_id' => 3,
                'role_id' => 1
            ],
            [
                'user_id' => 4,
                'role_id' => 1
            ],
            [
                'user_id' => 5,
                'role_id' => 5
            ],
            [
                'user_id' => 6,
                'role_id' => 3
            ],
        ]);
    }
}
