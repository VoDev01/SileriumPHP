<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
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
    public function testProductsIndex()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        Seller::factory()->has(Product::factory(15))->create();

        $response = $this->getJson('/api/v1/product/index/15');

        $product = $response['data'][0];

        $response
            ->assertOk()   
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 15, fn ($json) =>
                    $json->where('id', $product['id'])
                        ->where('name', $product['name'])
                        ->where('subcategory_id', $product['subcategory_id'])
                        ->etc()
                )
                ->etc()
        );
    }

    public function testProductsShow()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(15))->create();

        $response = $this->getJson('/api/v1/product/show/' . Product::max('id'));

        $response
            ->assertOk()   
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('0.id', $seller->products->last()->id)
                    ->where('0.name', $seller->products->last()->name)
                    ->etc()
        );
    }

    public function testProductsCreate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->postJson('/api/v1/product/create/', [
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

    public function testProductsUpdate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->putJson('/api/v1/product/update', [
            'id' => $product->id,
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

    public function testProductsByNameSeller()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->getJson('/api/v1/product/name_seller/' . $seller->nickname . '/' . $product->name);

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('0.id', $product->id)
                    ->where('0.name', $product->name)
                    ->etc()
        );
    }

    public function testProductsDelete()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();

        $response = $this->deleteJson('/api/v1/product/delete', ['id' => $product->id]);

        $response->assertOk();
    }

    public function testProductsReviewSearch()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $product = $seller->products->first();
    }
}