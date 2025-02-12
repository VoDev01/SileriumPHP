<?php

namespace App\Http\Controllers\Payment;

use YooKassa\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\RefundRequest;
use App\Models\Order;
use App\Models\Refund;
use App\Services\PaymentService;
use Carbon\Carbon;

class RefundController extends Controller
{
    public function listRefunds()
    {

    }
    public function refund(RefundRequest $request)
    {
        return PaymentService::refund($request, ['login' => '1026235', 'password' => 'test_dfoZBZwwXDoC1gXWqeg_wNhYFsAqv-dU91hezVx04Y0']);
    }
}