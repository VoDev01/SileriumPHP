<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert(
            [
                'name' => 'Iphone X Pro',
                'description' => 'Iphone X Pro',
                'priceRub' => 50000,
                'stockAmount' => 5,
                'available' => true,
                'subcategory_id' => 1,
                'specification_id' => 1
            ]
        );
    }
}
