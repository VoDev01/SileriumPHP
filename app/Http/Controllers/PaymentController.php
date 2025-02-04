<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\PaymentRequest;
use YooKassa\Client;

class PaymentController extends Controller
{
    public function credentialsForm()
    {
        return view('payment.credentialsForm', ['confirmationToken' => session('confirmationToken'), 'orderId' => session('orderId')]);
    }
    public function sendConfirmationToken(Request $request)
    {
        return view('payment.sendConfirmationToken', ['confirmationToken' => $request->confirmationToken, 'orderId' => $request->orderId]);
    }
    public function receiveOrderId(Request $request)
    {
        return view('payment.receiveOrderId', ['orderId' => $request->orderId]);
    }
    public function createPaymentRequest(PaymentRequest $request)
    {
        $validated = $request->validated();
        $order = Order::with(['user', 'products'])->where('ulid', $validated['orderId'])->get()->first();
        $idempotenceKey = uniqid('', true);
        $client = new Client();
        $client->setAuth('1026235', 'test_dfoZBZwwXDoC1gXWqeg_wNhYFsAqv-dU91hezVx04Y0');
        $response = $client->createPayment(
            [
                'amount' => [
                    'value' => $order->totalPrice,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'embedded',
                ],
                'capture' => true,
                'description' => 'Заказ ' . $order->id,
                'metadata' => [
                    'order_id' => $order->id
                ] 
            ],
            $idempotenceKey,
        );
        $confirmationToken = $response->getConfirmation()->getConfirmationToken();
        return redirect()->route('payment.sendConfirmationToken', ['confirmationToken' => $confirmationToken, 'orderId' => $order->ulid]);
    }
    public function receiveConfirmationToken(Request $request)
    {
        return redirect('/payment/credentials')->with('confirmationToken', $request->confirmationToken)->with('orderId', $request->orderId);
    }
    public function finished()
    {
        $order = Order::where('ulid', session('orderId'))->get()->first();
        return view('payment.finished', ['order' => $order]);
    }
}
