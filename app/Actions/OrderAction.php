<?php
namespace App\Actions;

use App\Models\Order;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderAction 
{
    public static function make(string $address, string $status, int $user_id)
    {
        $cartContent = Cart::session($user_id)->getContent();
        $insert = array_merge(['ulid' => Str::ulid()->toBase32()], compact('address', 'status', 'user_id'));
        $orderId = Order::create(array_merge($insert, ['totalPrice' => 0.0]))->ulid;
        $totalOrderPrice = null;
        foreach($cartContent as $product) { 
            $productsPrice = $product->model->priceRub * $product->quantity;
            $totalOrderPrice += $productsPrice;
            DB::insert('INSERT INTO orders_products (order_id, product_id, productAmount, productsPrice) VALUES (?, ?, ?, ?)', 
            [
                $orderId,
                $product->model->id, 
                $product->quantity,
                $productsPrice
            ]);
        }
        Order::where('ulid', $orderId)->update(['totalPrice' => $totalOrderPrice]);
        Cart::session($user_id)->clear();
        return Order::where('ulid', $orderId)->get()->first();
    }
}