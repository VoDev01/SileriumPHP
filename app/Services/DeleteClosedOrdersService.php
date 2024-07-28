<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class DeleteClosedOrdersService
{
    public function delete()
    {
        $orders = Order::all();
        foreach($orders as $order)
        {
            if($order->updated_at->diffInDays(Carbon::now()) >= 7 && $order->updated_at->diffInDays(Carbon::now()) >= 7)
            {
                $order->forceDelete();
            }
        }
    }
}