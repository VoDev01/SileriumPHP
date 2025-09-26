<?php

namespace App\Actions;

use Carbon\Carbon;

class DeleteClosedOrdersAction
{
    public static function delete($orders)
    {
        foreach($orders as $order)
        {
            if(Carbon::now()->diffInDays($order->deleted_at) >= 7 && $order->status == "CANCELLED")
                $order->forceDelete();
        }
    }
}
