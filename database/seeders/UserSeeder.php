<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(5)->create();
        Seller::factory()->count(1)->create(['user_id' => User::max('id')]);
        $role = Role::where('role', 'admin')->get()->first();
        User::factory()->hasAttached($role)->create(['email' => 'vodev1405@gmail.com', 'password' => 'sileriumdev123']);
    }
}
