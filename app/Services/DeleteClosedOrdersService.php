<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DeleteClosedOrdersService
{
    public static function delete()
    {
        Order::onlyTrashed()->where('user_id', Auth::id())->where('deleted_at', '<=', Carbon::now())->forceDelete();
    }
}
