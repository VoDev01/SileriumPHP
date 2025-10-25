<?php

namespace Tests\Feature\API;

use App\Enum\TestRouteMethods;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\APIUser;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $product = Seller::factory()->has(Product::factory(20))->create()->first();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI('/api/v1/products/index/15', $this);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'data',
                    15,
                    fn($json) =>
                    $json->where('id', $product->id)
                        ->etc()
                )
                    ->etc()
            );
    }

    public function testShow()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(15))->create();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI('/api/v1/products/show/' . $seller->products->first()->ulid, $this);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('ulid', $seller->products->first()->ulid)
                    ->etc()
            );
    }

    public function testCreate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/create/',
            $this,
            TestRouteMethods::POST,
            [
                'name' => $product->name,
                'description' => $product->description,
                'priceRub' => $product->priceRub,
                'available' => $product->available,
                'productAmount' => $product->productAmount,
                'subcategory_id' => $product->subcategory_id,
                'seller_id' => $seller->id
            ]
        );

        $this->assertDatabaseHas('products', [
            'name' => $product->name,
            'description' => $product->description,
            'priceRub' => $product->priceRub,
            'available' => $product->available,
            'productAmount' => $product->productAmount,
            'subcategory_id' => $product->subcategory_id,
            'seller_id' => $seller->id
        ]);
    }

    public function testUpdate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/update',
            $this,
            TestRouteMethods::PATCH,
            [
                'id' => $product->ulid,
                'name' => $product->name,
                'description' => $product->description,
                'priceRub' => $product->priceRub,
                'available' => $product->available,
                'productAmount' => $product->productAmount,
                'subcategory_id' => $product->subcategory_id
            ]
        );
    }

    public function testDelete()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/delete',
            $this,
            TestRouteMethods::DELETE,
            [
                'id' => $product->ulid
            ]
        );
    }

    public function testSearch()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/search/',
            $this,
            TestRouteMethods::POST,
            [
                'sellerName' => $seller->nickname,
                'productName' => $product->name
            ]
        );

        $response
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'products',
                    1,
                    fn($json) =>
                    $json->where('ulid', $product->ulid)
                        ->etc()
                )
                    ->etc()
            );
    }
    public function testProductsProfitBetweenDate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $seller = Seller::factory()->has(Product::factory(10))->create();
        $products = $seller->products;
        $orders = Order::factory()->hasAttached($products->first(), ['productAmount' => 10, 'productsPrice' => 10 * $products->first()->priceRub])->for($user)->create();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/profit_between_date/',
            $this,
            TestRouteMethods::POST,
            [
                'productName' => $products->first()->name,
                'sellerName' => $seller->nickname,
                'lowerDate' => Carbon::now()->subMonths(12)->toDateTime()->format('Y-m-d H:i:s'),
                'upperDate' => Carbon::now()->addDay()->toDateTime()->format('Y-m-d H:i:s')
            ]
        );

        $response
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'profits',
                    1,
                    fn($json) =>
                    $json->where('id', $products->first()->id)
                        ->etc()
                )->etc()
            );
    }
    public function testProductsConsumptionBetweenDate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $seller = Seller::factory()->has(Product::factory(10))->create();
        $products = $seller->products;
        $orders = Order::factory()->hasAttached($products->first(), ['productAmount' => 10, 'productsPrice' => 10 * $products->first()->priceRub])->for($user)->create();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/consumption_between_date/',
            $this,
            TestRouteMethods::POST,
            [
                'productName' => $products->first()->name,
                'sellerName' => $seller->nickname,
                'lowerDate' => Carbon::now()->subMonths(12)->toDateTime()->format('Y-m-d H:i:s'),
                'upperDate' => Carbon::now()->addDay()->toDateTime()->format('Y-m-d H:i:s')
            ]
        );

        $response
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'consumption',
                    1,
                    fn($json) =>
                    $json->where('product_id', $products->first()->id)
                        ->etc()
                )->etc()
            );
    }
    public function testProductsAmountExpiry()
    {

        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $seller = Seller::factory()->has(Product::factory(10))->create();
        $products = $seller->products;
        $orders = Order::factory()->hasAttached($products->first(), ['productAmount' => 10, 'productsPrice' => 10 * $products->first()->priceRub])->for($user)->create();
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/products/est_amount_expiry/',
            $this,
            TestRouteMethods::POST,
            [
                'productName' => $products->first()->name,
                'sellerName' => $seller->nickname,
                'lowerDate' => Carbon::now()->subMonths(12)->toDateTime()->format('Y-m-d H:i:s'),
                'upperDate' => Carbon::now()->addDay()->toDateTime()->format('Y-m-d H:i:s')
            ]
        );

        $response
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'expiresAt',
                    1,
                    fn($json) =>
                    $json->where('product_id', $products->first()->id)
                        ->etc()
                )->etc()
            );
    }
}
