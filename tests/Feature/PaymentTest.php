<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPayment()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $seller = Seller::factory()->has(Product::factory())->create();

        $response = $this->actingAs($user)->post('/user/cart/add_to_cart', ['amount' => 10, 'productId' => $seller->products->first()->id]);

        $this->assertFalse(Cart::session($user->id)->isEmpty());

        $response = $this->actingAs($user)->post('/user/orders/checkout_order');

        $response = $response->assertRedirect(route('payment.createPayment'));

        // //$response = $response->assertRedirect(route('payment.receiveOrderId'));

        // $response = $response->assertRedirect(route('payment.createPaymentRequest'));

        // $response = $response->assertRedirect(route('payment.sendConfirmationToken'));

        // $response = $response->assertRedirect(route('payment.receiveConfirmationToken'));

        // $response = $response->assertRedirect('/payment/credentials');
    }
}
