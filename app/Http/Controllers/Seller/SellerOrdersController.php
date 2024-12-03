<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class SellerOrdersController extends Controller
{
    public function orders()
    {
        $orders = Order::with('user')->where('seller_id', session('seller_id'))->get();
        return view('seller.orders.orders', ['orders' => $orders]);
    }
}
