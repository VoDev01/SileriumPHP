<?php
namespace App\Services;

use App\Models\Order;
use App\Enum\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MakeOrderService 
{
    public static function make(string $order_adress, $total_price, OrderStatus $order_status, int $user_id, array $products)
    {
        $insert = array_merge(['ulid' => Str::ulid()->toBase32()], compact($order_adress, $total_price, $order_status, Auth::id()));
        $orderId = Order::insertGetId($insert);
        for ($i=0; $i < count($products); $i++) { 
            DB::insert('INSERT INTO orders_products (order_id, product_id, product_amount) VALUES (?, ?, ?)', 
            [
                $orderId, 
                $products[$i]['id'], 
                $products[$i]['amount']
            ]);
        }
        $order = Order::find($orderId);
        return $order;
    }
}