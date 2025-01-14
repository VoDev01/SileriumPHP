<?php
namespace App\Services;

use App\Models\Order;
use App\Enum\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService 
{
    public function make(string $order_adress, OrderStatus $order_status, int $user_id, array $products)
    {
        $insert = array_merge(['ulid' => Str::ulid()->toBase32()], compact($order_adress, $order_status, $user_id));
        $orderId = Order::insertGetId(array_merge($insert, ['totalPrice' => null]));
        $totalPrice = null;
        for ($i=0; $i < count($products); $i++) { 
            $totalPrice += $products[$i]['priceRub'] * $products[$i]['amount'];
            DB::insert('INSERT INTO orders_products (order_id, product_id, product_amount, totalPrice) VALUES (?, ?, ?, ?)', 
            [
                $orderId, 
                $products[$i]['id'], 
                $products[$i]['amount'],
                $products[$i]['amount'] * $products[$i]['amount']
            ]);
        }
        //Order::where('id', $orderId)->update(['totalPrice' => $totalPrice]);
        $order = Order::find($orderId);
        return $order;
    }
}