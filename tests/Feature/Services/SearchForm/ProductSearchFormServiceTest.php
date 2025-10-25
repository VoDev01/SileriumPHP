<?php

namespace Tests\Feature\Services\SearchForm;

use App\Enum\SortOrder;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductSearchFormServiceTest extends TestCase
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

        $user = User::factory()->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create();

        $response = $this->actingAs($user)->post('/catalog/products/search', [
            'productName' => $product->name,
            'sortOrder' => SortOrder::NAME_ASC->value,
            'page' => 1
        ]);

        $response->assertRedirect();

        $this->assertTrue(Cache::has('products_' . SortOrder::NAME_ASC->value . '_1'));
    }
}
