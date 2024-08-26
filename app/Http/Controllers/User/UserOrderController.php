<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DeleteClosedOrdersService;

class UserOrderController extends Controller
{
    public function editOrder(Order $order)
    {
        return view("user.editorder", ['order' => $order]);
    }
    public function postEditOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->save();
        return redirect()->route('cart');
    }
    public function closeOrder(Request $request)
    {
        Order::where('ulid', $request->orderId)->first()->delete();
        return redirect()->route('cart');
    }
    public function ordersHistory()
    {
        $orders = Order::with(['products', 'products.images'])->withTrashed()->get();
        DeleteClosedOrdersService::delete();
        return view('user.orders.ordershistory', ['orders' => $orders]);
    }
}
