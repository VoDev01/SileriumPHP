<?php

namespace App\Services;

use Carbon\Carbon;

class DeleteClosedOrdersService
{
    public static function delete($orders)
    {
        foreach($orders as $order)
        {
            if(Carbon::now()->diffInDays($order->deleted_at) >= 7 && $order->orderStatus == "CLOSED")
                $order->forceDelete();
        }
    }
}
