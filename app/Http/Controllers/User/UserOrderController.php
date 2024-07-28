<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class UserOrderController extends Controller
{
    public function cart()
    {
        $products = Cart::session(Auth::id())->getContent();
        return view('user.cart', ['products' => $products]);
    }
    public function changeAmount(Request $request)
    {
        ChangeCartAmount::changeAmount($request->amount, $request->amountChange, $request->productId, Auth::id());
        return redirect()->route('cart');
    }
    public function filterCart(Request $request)
    {
        $orders = Order::all()->where('orderStatus', $request->orderStatus);
        return redirect()->route('/user/cart', ['orders' => $orders]);
    }
    public function removeFromCart(Request $request)
    {
        Cart::session(Auth::id())->remove($request->productId);
        return redirect()->route('cart');
    }
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
        $order = Order::find($request->orderId);
        $order->orderStatus = OrderStatus::CLOSED;
        $order->delete();
        return redirect()->route('cart');
    }
    public function ordersHistory()
    {
        $orders = Order::withTrashed()->get();
        DeleteClosedOrders::delete();
        return view('user.ordershistory', ['orders' => $orders]);
    }
}
