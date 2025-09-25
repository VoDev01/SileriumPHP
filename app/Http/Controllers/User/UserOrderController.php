<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\DeleteClosedOrdersAction;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{
    public function editOrder(Order $order)
    {
        return view("user.editorder", ['order' => $order]);
    }
    public function postEditOrder(Request $request)
    {
        $productsPrice = $request->amount * $request->basePrice;
        $productId = DB::select('SELECT p.id FROM orders 
        INNER JOIN orders_products AS op ON orders.ulid = op.order_id 
        INNER JOIN products AS p ON op.product_id = p.id 
        WHERE orders.ulid = ?', [$request->orderId]);
        DB::update('UPDATE orders_products SET productAmount = ?, productsPrice = ? WHERE product_id = ?', [
            $request->amount,
            $productsPrice,
            $productId
        ]);
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
        $orders = Order::with('payment')->withTrashed()->where('user_id', Auth::id())->with(['products', 'products.images'])->get();
        DeleteClosedOrdersAction::delete($orders);
        return view('user.orders.ordershistory', ['orders' => $orders]);
    }
    public function checkoutOrder()
    {
        return redirect()->route('payment.createPayment');
    }
    public function refund(Request $request)
    {
        $order = Order::with(['products', 'products.images'])->where('ulid', $request->orderId)->get()->first();
        $paymentId = Payment::where('order_id', $order->ulid)->get()->first()->payment_id;
        return view('user.orders.refund', ['order' => $order, 'paymentId' => $paymentId]);
    }
}