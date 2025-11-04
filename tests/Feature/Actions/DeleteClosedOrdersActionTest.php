<?php

namespace Tests\Feature\Actions;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use App\Actions\DeleteClosedOrdersAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteClosedOrdersActionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDelete()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $order = Order::factory()->for($user)->hasAttached($seller->products, [
            'productAmount' => 1,
            'productsPrice' => $seller->products->first()->priceRub
        ])->create();

        $orderUlid = $order->ulid;

        $order->delete();

        $order->save();

        Carbon::setTestNow(Carbon::now()->addDay());

        DeleteClosedOrdersAction::delete(Order::where('ulid', $orderUlid)->withTrashed()->get()->first());

        $this->assertNotEmpty(DB::select('SELECT * FROM orders WHERE ulid = ?', [$order->ulid]));

        Carbon::setTestNow(Carbon::now()->addDays(7));
        
        DeleteClosedOrdersAction::delete(Order::where('ulid', $orderUlid)->withTrashed()->get()->first());

        $this->assertEmpty(DB::select('SELECT * FROM orders WHERE ulid = ?', [$order->ulid]));

        Carbon::setTestNow();
    }
}
