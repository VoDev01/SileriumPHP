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
                'role' => 'admin'
            ],
            [
                'role' => 'moderator'
            ],
            [
                'role' => 'seller'
            ]
        ]);
    }
}
