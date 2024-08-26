<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserOrderTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCloseOrder()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->hasAttached($product, ['productAmount' => 5, 'totalPrice' => $product->priceRub * 5])->create();

        $this->actingAs($user)->delete('/user/orders/closeorder', ['orderId' => $order->ulid]);

        $this->assertDatabaseHas('orders', ['ulid' => $order->ulid, 'orderStatus' => 'CLOSED']);
    }
    public function testOrdersHistory()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->create();
        $product = Product::factory()->has(ProductImage::factory(), 'images')->create();
        $order = Order::factory()->hasAttached($product, ['productAmount' => 5, 'totalPrice' => $product->priceRub * 5])->create();

        $this->actingAs($user)->get('/user/order/history');

        $this->assertDatabaseHas('orders', ['ulid' => $order->ulid]);

        $this->actingAs($user)->delete('/user/orders/closeorder', ['orderId' => $order->ulid]);

        $this->assertSoftDeleted('orders', ['ulid' => $order->ulid]);

        Carbon::setTestNow(Carbon::now()->addDays(8)); 

        $this->actingAs($user)->get('/user/orders/history');

        $this->assertDatabaseMissing('orders', ['ulid' => $order->ulid]);

        Carbon::setTestNow();
    }
}
