<?php

namespace Tests\Feature\Services\SearchForm;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Enum\TestRouteMethods;
use App\Services\SearchForms\PaymentSearchFormService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentSearchFormServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $product = Seller::factory()->has(Product::factory(20))->create()->products->first();

        $user = User::factory(15)->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create()->first();

        $orders = Order::factory(15)->for($user)->hasAttached(
            $product,
            [
                'productAmount' => 1,
                'productsPrice' => $product->priceRub
            ],
            'products'
        )
        ->has(Payment::factory())
        ->create();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = (new PaymentSearchFormService)->search([
            'id' => $user->ulid,
            'email' => $user->email
        ]);

        $this->assertTrue(Cache::has('payments'));
    }
}
