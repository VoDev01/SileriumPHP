<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCartTest extends TestCase
{
    use RefreshDatabase;
    public function testAddToCart()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $product = Seller::factory()->has(Product::factory())->create()->products()->first();

        $this->actingAs($user)->post('/user/cart/add_to_cart', ['amount' => 5, 'productId' => $product->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());
    }
    public function testChangeCartAmount()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $product = Seller::factory()->has(Product::factory())->create()->products()->first();

        $this->actingAs($user)->post('/user/cart/add_to_cart', ['amount' => 5, 'productId' => $product->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());

        $this->actingAs($user)->post('/user/cart/change_amount', ['amount' => 5, 'amountChange' => 'up', 'productId' => $product->id]);

        $this->assertGreaterThan(5, Cart::session($user->id)->get($product->id)['quantity']);
    }
    public function testRemoveFromCart()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $product = Seller::factory()->has(Product::factory())->create()->products()->first();

        $this->actingAs($user)->post('/user/cart/add_to_cart', ['amount' => 5, 'productId' => $product->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());

        $this->actingAs($user)->delete('/user/cart/remove_from_cart', ['productId' => $product->id]);

        $this->assertTrue(Cart::session($user->id)->isEmpty());
    }
}
