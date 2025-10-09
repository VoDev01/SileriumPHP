<?php

namespace App\Actions;

use Carbon\Carbon;

/**
 * Deletes orders in database that are older than 7 days before accessing history
 */
class DeleteClosedOrdersAction
{
    /**
     * Delete old orders
     *
     * @param mixed $orders
     * @return array
     */
    public static function delete($orders)
    {
        foreach($orders as $order)
        {
            if(Carbon::now()->diffInDays($order->deleted_at) >= 7 && $order->status == "CANCELLED")
                $order->forceDelete();
        }
        return $orders->toArray();
    }
}
