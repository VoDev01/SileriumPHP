<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
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
        $user = User::factory()->has(Role::factory())->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $order = Order::factory()->for($user)->create();

        $this->actingAs($user)->delete('/user/orders/close_order', ['ulid' => $order->ulid]);

        $order = Order::onlyTrashed()->where('ulid', $order->ulid)->first();

        $this->assertSoftDeleted('orders', ['ulid' => $order->ulid]);

        $this->assertEquals('CLOSED', $order->orderStatus);
    }
    public function testOrdersHistory()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $order = Order::factory()->for($user)->create();

        $this->actingAs($user)->get('/user/orders/history');

        $this->assertDatabaseHas('orders', ['ulid' => $order->ulid]);

        $this->actingAs($user)->delete('/user/orders/close_order', ['ulid' => $order->ulid]);
        
        $order = Order::onlyTrashed()->where('ulid', $order->ulid)->first();

        $this->assertSoftDeleted('orders', ['ulid' => $order->ulid]);

        $this->assertEquals('CLOSED', $order->orderStatus);

        Carbon::setTestNow(Carbon::now()->addDays(8)); 

        $this->actingAs($user)->get('/user/orders/history');

        $this->assertDatabaseMissing('orders', ['ulid' => $order->ulid]);

        Carbon::setTestNow();
    }
}
