<?php

namespace Tests\Feature\Seller;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\Seller;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SellerOrdersTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearchOrders()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $sellerUser = User::factory()->hasAttached(Role::factory()->create(['role' => 'seller']))->create();
        $seller = Seller::factory()->has(Product::factory())->for($sellerUser)->create();
        $product = $seller->products->first();
        
        $user = User::factory()->create();

        $orders = Order::factory(15)->hasAttached($product, [
            'productAmount' => 1,
            'productsPrice' => $product->priceRub
        ])->for($user)->create();

        $response = $this->actingAs($sellerUser)->post('/seller/orders/search', [
            'sellerName' => $seller->nickname,
            'productName' => $product->name,
            'loadWith' => 'orders'
        ]);

        $response->assertRedirect();

        $response = $this->actingAs($sellerUser)->get('/seller/orders/list');

        $this->assertTrue(Cache::has('products'));

        $this->assertTrue(Cache::get('products')[0]->orders->diff($orders)->isEmpty());
    }
}
