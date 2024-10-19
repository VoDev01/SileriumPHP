<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\DeleteClosedOrdersService;

class UserOrderController extends Controller
{
    public function editOrder(Order $order)
    {
        return view("user.editorder", ['order' => $order]);
    }
    public function postEditOrder(Request $request)
    {
        $order = Order::find($request->orderId);
        $order->save();
        return redirect()->route('cart');
    }
    public function closeOrder(Request $request)
    {
        $order = Order::find($request->ulid);
        $order->delete();
        return redirect()->route('cart');
    }
    public function ordersHistory()
    {
        $orders = Order::withTrashed()->where('user_id', Auth::id())->with(['products', 'products.images'])->get();
        DeleteClosedOrdersService::delete($orders);
        return view('user.orders.ordershistory', ['orders' => $orders]);
    }
}
