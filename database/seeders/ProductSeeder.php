<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->count(30)->has(ProductImage::factory(), 'images')->create(['seller_id' => Seller::max('id')]);
    }
}
