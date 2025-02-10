<?php

namespace App\Http\Controllers\Payment;

use YooKassa\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\RefundRequest;
use App\Models\Order;

class RefundController extends Controller
{
    public function listRefunds()
    {

    }
    public function refund(RefundRequest $request)
    {
        $validated = $request->validated();
        $order = Order::find($validated['orderId']);
        $client = new Client();
        $client->setAuth('1026235', 'test_dfoZBZwwXDoC1gXWqeg_wNhYFsAqv-dU91hezVx04Y0');
        $response = $client->createRefund([
            array(
                'amount' => array(
                    'value' => $order->totalPrice,
                    'currency' => 'RUB'
                ),
                'payment_id' => $validated['paymentId']
            )
        ], uniqid('', true));
        
    }
}
