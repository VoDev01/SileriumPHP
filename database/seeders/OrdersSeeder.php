<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory(30)
            ->hasAttached(Product::where('id', 1,)->get()->first(), [
                'productAmount' => rand(1, 1000),
                'productsPrice' => rand(1000, 1000000)
            ], 'products')
            ->for(User::where('id', 1)->get()->first())->create();
    }
}
