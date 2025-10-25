<?php

namespace Tests\Feature\Product;

use App\Enum\SortOrder;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class CatalogTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProducts()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $userSeller = User::factory()->hasAttached(
            Role::factory()
                ->create(['role' => 'seller']),
            relationship: 'roles'
        )
            ->has(
                Seller::factory()
                    ->has(Product::factory(15))
            )->create();

        $product = $userSeller->seller->products->first();

        $response = $this->get('/catalog/products');

        $response->assertOk();

        $response->assertViewHas('products', function (LengthAwarePaginator $productsPage) use ($product)
        {
            foreach ($productsPage->items() as $productPage)
            {
                if ($productPage->id === $product->id)
                    return true;
            }
            return false;
        });
    }

    public function testProduct()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $userSeller = User::factory()
            ->hasAttached(
                Role::factory()
                    ->create(['role' => 'seller']),
                relationship: 'roles'
            )
            ->has(
                Seller::factory()
                    ->has(Product::factory(15))
            )->create();

        $user = User::factory()->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create();

        $product = (new ProductRepository)->show($userSeller->seller->products->first()->ulid);

        $response = $this->actingAs($user)->get('/catalog/product/' . $product->ulid);

        $response->assertOk();

        $response->assertViewHas('product', $product);
    }

    public function testFilterProducts()
    {
        Category::factory()->create();
        $subcategory = Subcategory::factory()->create();
        $userSeller = User::factory()->hasAttached(Role::factory()->create(['role' => 'seller']), relationship: 'roles')
            ->has(Seller::factory()->has(Product::factory(15)))->create();

        $assertOrder = function ($products, int $sortOrder)
        {
            $cache = Cache::get("products_$sortOrder")->items();
            foreach ($products as $key => $val)
            {
                if (array_key_exists($key, $cache))
                {
                    if ($val === $cache[$key]->name)
                    {
                        continue;
                    }
                }
                return false;
            }
            return true;
        };

        $products = array_map(function ($elem)
        {
            return $elem['name'];
        }, $userSeller->seller->products->toArray());

        $response = $this->get(
            '/catalog/products' . '?' . http_build_query(
                [
                    'sortOrder' => SortOrder::NAME_ASC->value,
                    'available' => true,
                ]
            )
        );

        $response->assertOk();

        sort($products);

        $this->assertTrue(call_user_func($assertOrder, $products, SortOrder::NAME_ASC->value));

        $response = $this->get(
            '/catalog/products' . '?' . http_build_query(
                [
                    'sortOrder' => SortOrder::NAME_DESC->value,
                    'available' => true,
                ]
            )
        );

        $response->assertOk();

        rsort($products);

        $this->assertTrue(call_user_func($assertOrder, $products, SortOrder::NAME_DESC->value));
    }
}
