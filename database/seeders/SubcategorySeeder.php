<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subcategories')->insert(
            [
                'name' => 'Iphone',
                'image' => '/images/subcategories/subcategory_1.jpg',
                'category_id' => '1'
            ]
        );
    }
}
