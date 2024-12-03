<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
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
        $product = Seller::factory()->has(Product::factory(20))->create()->first();

        $response = $this->actingAs($user)->getJson('/api/v1/products/index/15');

        $response
            ->assertOk()   
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 15, fn ($json) =>
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
        $seller = Seller::factory()->has(Product::factory(15))->create();

        $response = $this->actingAs($user)->getJson('/api/v1/products/show/' . Product::max('id'));

        $response
            ->assertOk()   
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('product', 1, fn ($json) =>
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
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->actingAs($user)->postJson('/api/v1/products/create/', [
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
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->actingAs($user)->putJson('/api/v1/products/update', [
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
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->actingAs($user)->deleteJson('/api/v1/products/delete', ['id' => $product->ulid]);

        $response->assertOk();
    }
    
    public function testSearchByNameSeller()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->actingAs($user)->postJson('/api/v1/products/by_name_seller/', [
            'sellerName' => $seller->nickname,
            'productName' => $product->name
        ]);

        $response->assertSessionHasNoErrors();

        $response
            ->assertOk()   
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('products', 1, fn ($json) =>
                    $json->where('id', $product->id)
                        ->etc()
                )
                ->etc()
        );
    }
}