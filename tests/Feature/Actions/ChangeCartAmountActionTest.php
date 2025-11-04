<?php

namespace Tests\Feature\Actions;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Actions\ChangeCartAmountAction;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeCartAmountActionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testChangeCartAmount()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $product = Seller::factory()->has(Product::factory())->create()->products()->first();

        Cart::session($user->id)->add([
            'id' => $product->id, 
            'name' => $product->name, 
            'price' => $product->priceRub, 
            'quantity' => 10,
            'associatedModel' => 'Product'
        ]);

        $this->assertTrue(Cart::session($user->id)->has($product->id));

        ChangeCartAmountAction::changeAmount(Cart::session($user->id)->get($product->id)['quantity'], 'up', $product->id, $user->id);

        $this->assertTrue(Cart::session($user->id)->get($product->id)['quantity'] == 11);
    }
}
