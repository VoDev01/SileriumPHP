<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Tests\TestCase;

class UserCartTest extends TestCase
{
    use RefreshDatabase;
    public function testAddToCart()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/user/cart/postcart', ['amount' => 5, 'productId' => $product->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());
    }
    public function testChangeCartAmount()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/user/cart/postcart', ['amount' => 5, 'productId' => $product->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());

        $this->actingAs($user)->post('/user/cart/changeamount', ['amount' => 5, 'amountChange' => 'up', 'productId' => $product->id]);

        $this->assertGreaterThan(5, Cart::session($user->id)->get($product->id)['quantity']);
    }
    public function testRemoveFromCart()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/user/cart/postcart', ['amount' => 5, 'productId' => $product->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());

        $this->actingAs($user)->delete('/user/cart/removefromcart', ['productId' => $product->id]);

        $this->assertTrue(Cart::session($user->id)->isEmpty());
    }
}
