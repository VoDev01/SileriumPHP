<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

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
        Seller::factory()->count(1)->create(['user_id' => User::max('id'), 'logo' => '/media/images/logo/default.png']);
        $role = Role::where('role', 'admin')->get()->first();
        User::factory()->hasAttached($role)->create(['email' => 'vodev1405@gmail.com', 'password' => Hash::make('sileriumdev123', ["rounds" => 12])]);
    }
}
