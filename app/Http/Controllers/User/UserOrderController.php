<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function cart()
    {
        $products = Cart::session(Auth::id())->getContent();
        return view('user.cart', ['products' => $products]);
    }
    public function changeAmount(Request $request)
    {
        $amount = $request->amount;
        if ($request->amount_change == "up") {
            $amount++;
        } else {
            $amount--;
        }
        $cartAmount = Cart::session(Auth::id())->get($request->product_id)->quantity;
        $totalAmount = $amount - $cartAmount;
        Cart::session(Auth::id())->update($request->product_id, array(
            'quantity' => $totalAmount
        ));
        return redirect()->route('cart');
    }
    public function filterCart(Request $request)
    {
        $orders = Order::all()->where('orderStatus', $request->order_status);
        return redirect()->route('/user/cart', ['orders' => $orders]);
    }
    public function removeFromCart(Request $request)
    {
        Cart::session(Auth::id())->remove($request->product_id);
        return redirect()->route('cart');
    }
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
        $order = Order::find($request->order_id);
        $order->orderStatus = OrderStatus::CLOSED;
        $order->delete();
        $order->save();
        return redirect()->route('cart');
    }
    public function ordersHistory()
    {
        $orders = Order::onlyTrashed()->get();
        return view('user.ordershistory', ['orders' => $orders]);
    }
}
