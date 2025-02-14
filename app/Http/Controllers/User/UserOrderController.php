<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Enum\OrderStatus;
use App\Actions\OrderAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\DeleteClosedOrdersAction;
use Illuminate\Database\Eloquent\Collection;
use Darryldecode\Cart\Facades\CartFacade as Cart;

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
        DeleteClosedOrdersAction::delete($orders);
        return view('user.orders.ordershistory', ['orders' => $orders]);
    }
    public function checkoutOrder()
    {
        $user = Auth::user();
        $order = OrderAction::make($user->homeAdress, 'Pending', $user->id);
        return redirect()->route('payment.receiveOrderId', ['orderId' => $order->ulid]);
    }
    public function refund(Request $request)
    {
        $order = Order::with(['products', 'products.images', 'user'])->where('ulid', $request->orderId)->get()->first();
        $paymentId = Payment::where('user_id', $order->user->ulid)->get()->first()->payment_id;
        return view('user.orders.refund', ['order' => $order, 'paymentId' => $paymentId]);
    }
}
