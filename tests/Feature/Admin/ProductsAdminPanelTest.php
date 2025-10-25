<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Enum\TestRouteMethods;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class ProductsAdminPanelTest extends TestCase
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
        $seller = Seller::factory()->has(Product::factory(15))->create();
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = TestRouteForAuthService::testWeb('/admin/products/index', $this, $admin, TestRouteMethods::GET);

        $response->assertViewHas('products', $seller->products()->paginate(15)->onEachSide(5));
    }

    public function testUpdate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(15))->create();
        $product = $seller->products->first();
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = TestRouteForAuthService::testWeb(
            '/admin/products/update',
            $this,
            $admin,
            TestRouteMethods::PATCH,
            [
                'id' => $product->ulid,
                'name' => 'Product test'
            ]
        );

        $this->assertDatabaseHas('products', ['ulid' => $product->ulid, 'name' => 'Product test']);
    }

    public function testDelete()
    {

        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(15))->create();
        $product = $seller->products->first();
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = TestRouteForAuthService::testWeb(
            '/admin/products/delete',
            $this,
            $admin,
            TestRouteMethods::DELETE,
            ['id' => $product->ulid]
        );

        $this->assertDatabaseMissing('products', ['ulid' => $product->ulid]);
    }
}
