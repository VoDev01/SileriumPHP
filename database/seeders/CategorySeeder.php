<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Смартфоны', 
                'pageName' => 'smartphones',
                'image' => 'images\categories\category_0.jpg', 
            ],
            [
                'name' => 'Комплектующие ПК',
                'pageName' => 'hardware',
                'image' => 'images\categories\category_1.jpg'
            ],
            [
                'name' => 'Мониторы и телевизоры',
                'pageName' => 'monitors',
                'image' => 'images\categories\category_2.jpg'
            ],
            [
                'name' => 'Ноутбуки',
                'pageName' => 'laptops',
                'image' => 'images\categories\category_3.jpg'
            ]
            ];
        DB::table('categories')->insert($categories);
    }
}
