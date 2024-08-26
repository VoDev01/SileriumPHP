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
            'role' => 'user'
        ]);
        DB::table('roles')->insert([
            'role' => 'admin'
        ]);
        DB::table('users_roles')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);
    }
}
