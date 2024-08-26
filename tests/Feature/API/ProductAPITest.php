<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAPITest extends TestCase
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
        Product::factory()->count(15)->create();

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
        $product = Product::factory()->create();

        $response = $this->getJson('/api/v1/product/show/' . Product::max('id'));

        $response
            ->assertOk()   
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('0.id', $product->id)
                    ->where('0.name', $product->name)
                    ->etc()
        );
    }

    public function testProductsCreate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $product = Product::factory()->create();

        $response = $this->postJson('/api/v1/product/create/', [
            'name' => $product->name,
            'description' => $product->description,
            'priceRub' => $product->priceRub,
            'stockAmount' => $product->stockAmount,
            'available' => $product->available,
            'subcategory_id' => $product->subcategory_id
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('products', [
            'name' => $product->name,
            'description' => $product->description,
            'priceRub' => $product->priceRub,
            'stockAmount' => $product->stockAmount,
            'available' => $product->available,
            'subcategory_id' => $product->subcategory_id
        ]);
    }

    public function testProductsUpdate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $product = Product::factory()->create();

        $response = $this->putJson('/api/v1/product/update', [
            'id' => 1,
            'name' => $product->name,
            'description' => $product->description,
            'priceRub' => $product->priceRub,
            'stockAmount' => $product->stockAmount,
            'available' => $product->available,
            'subcategory_id' => $product->subcategory_id
        ]);

        $response->assertOk();
    }

    public function testProductsByNameId()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $product = Product::factory()->create();

        $response = $this->getJson('/api/v1/product/name_id/' . $product->name . '/' . $product->id);

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
        $product = Product::factory()->create();

        $response = $this->deleteJson('/api/v1/product/delete', ['id' => $product->id]);

        $response->assertOk();
    }
}