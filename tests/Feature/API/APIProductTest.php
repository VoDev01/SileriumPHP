<?php

namespace Tests\Feature\API;

use DateTime;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
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
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['index']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $product = Seller::factory()->has(Product::factory(20))->create()->first();

        // $response = $this->withHeader('Api-Secret', $secret)->getJson('/api/v1/products/index/15');

        // $response->assertForbidden();

        $response = $this->withHeader('Api-Secret', $secret)->getJson('/api/v1/products/index/15');

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
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['show']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory(15))->create();

        // $response = $this->withHeader('Api-Secret', $secret)->getJson('/api/v1/products/show/' . Product::max('id'));

        // $response->assertForbidden();

        $response = $this->withHeader('Api-Secret', $secret)->getJson('/api/v1/products/show/' . Product::max('id'));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'product',
                    1,
                    fn($json) =>
                    $json->where('id', $seller->products->last()->id)
                        ->where('name', $seller->products->last()->name)
                        ->etc()
                )
                    ->etc()
            );
    }

    public function testCreate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'seller']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['create']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        // $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/create/');

        // $response->assertForbidden();

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/create/', [
            'name' => $product->name,
            'description' => $product->description,
            'priceRub' => $product->priceRub,
            'available' => $product->available,
            'productAmount' => $product->productAmount,
            'subcategory_id' => $product->subcategory_id,
            'seller_id' => $seller->id
        ]);

        $response->assertOk();

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
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['update']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        // $response = $this->putJson('/api/v1/products/update/');

        // $response->assertForbidden();

        $response = $this->withHeader('Api-Secret', $secret)->patchJson('/api/v1/products/update', [
            'id' => $product->ulid,
            'name' => $product->name,
            'description' => $product->description,
            'priceRub' => $product->priceRub,
            'available' => $product->available,
            'productAmount' => $product->productAmount,
            'subcategory_id' => $product->subcategory_id,
            'seller_id' => $seller->id
        ]);

        $response->assertOk();
    }

    public function testDelete()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['delete']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        // $response = $this->withHeader('Api-Secret', $secret)->deleteJson('/api/v1/products/delete');

        // $response->assertForbidden();

        $response = $this->withHeader('Api-Secret', $secret)->deleteJson('/api/v1/products/delete', ['id' => $product->ulid]);

        $response->assertOk();
    }

    public function testSearchByNameSeller()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['create']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        // $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/by_name_seller/');

        // $response->assertForbidden();

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/by_name_seller/', [
            'sellerName' => $seller->nickname,
            'productName' => $product->name
        ]);

        $response->assertSessionHasNoErrors();

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'products',
                    1,
                    fn($json) =>
                    $json->where('id', $product->id)
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
        Passport::actingAs(
            $user,
            ['create']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory(10))->create();

        $products = $seller->products;
        $orders = Order::factory()->hasAttached($products->first(), ['productAmount' => 10]);

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/profit_between_date/', 
        [
            'productName' => $products->first()->name,
            'sellerName' => $seller->nickname,
            'lowerDate' => Carbon::now()->subMonths(12)->toDateTime()->format('Y-m-d H:i:s'),
            'upperDate' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s')
        ]);

        $response->assertOk()->assertJson(fn (AssertableJson $json) => 
            $json->has('products', 1, fn ($json) => $json->where('id', $products->first()->id)->etc())->etc()
        );
    }
    public function testProductsConsumptionBetweenDate()
    {

        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        Passport::actingAs(
            $user,
            ['create']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory(10))->create();

        $products = $seller->products;
        $orders = Order::factory()->hasAttached($products->first(), ['productAmount' => 10, 'productsPrice' => 10*$products->first()->priceRub])->for($user)->create();

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/consumption_between_date/', 
            [
                'productName' => $products->first()->name,
                'sellerName' => $seller->nickname,
                'lowerDate' => Carbon::now()->subMonths(12)->toDateTime()->format('Y-m-d H:i:s'),
                'upperDate' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s')
            ]);

        $response->assertOk()->assertJson(fn (AssertableJson $json) => 
            $json->has('consumption', 1, fn ($json) => 
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
        Passport::actingAs(
            $user,
            ['create']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $seller = Seller::factory()->has(Product::factory(10))->create();

        $products = $seller->products;
        $orders = Order::factory()->hasAttached($products->first(), ['productAmount' => 10, 'productsPrice' => 10*$products->first()->priceRub])->for($user)->create();

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/products/est_amount_expiry/',
        [
            'productName' => $products->first()->name,
            'sellerName' => $seller->nickname,
            'lowerDate' => Carbon::now()->subMonths(12)->toDateTime()->format('Y-m-d H:i:s'),
            'upperDate' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s')
        ]);

        $response->assertOk()->assertJson(fn (AssertableJson $json) => 
            $json->has('expiresAt', 1, fn ($json) => 
                $json->where('product_id', $products->first()->id)
                ->etc()
            )->etc()
        );
    }
}