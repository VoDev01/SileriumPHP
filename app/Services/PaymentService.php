<?php

namespace App\Services;

use Carbon\Carbon;
use YooKassa\Client;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Payment;
use App\Actions\OrderAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Actions\DisplayPaymentCancellationMessage;

class PaymentService
{
    public static function create(array $clientAuth)
    {
        $user = Auth::user();
        $order = OrderAction::make($user->homeAdress, 'Pending', $user->id);
        $idempotenceKey = uniqid('', true);
        $client = new Client();
        $client->setAuth($clientAuth['login'], $clientAuth['password']);
        $response = $client->createPayment(
            [
                'amount' => [
                    'value' => $order->totalPrice,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => 'https://silerium.com/payment/finished/' . $order->ulid
                ],
                'capture' => true,
                'description' => 'Заказ ' . $order->ulid,
                'metadata' => [
                    'order_id' => $order->ulid
                ]
            ],
            $idempotenceKey,
        );
        Payment::create([
            'payment_id' => $response->id,
            'order_id' => $order->ulid,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => $response->status
        ]);
        return redirect($response->getConfirmation()->getConfirmationUrl());
    }
    public static function cancel(Request $request)
    {
        $validated = $request->validated();
        if (isset($validated['paymentId']))
        {
            Payment::where('payment_id', $request->paymentId)->update(['status' => 'CANCELLED']);
            Payment::destroy($request->paymentId);
        }
        $order = Order::find($request->orderId);
        $order->delete();
    }
    public static function refund(Request $request, array $clientAuth)
    {
        $validated = $request->validated();
        $order = Order::with('user')->where('ulid', $validated['orderId'])->get()->first();
        $client = new Client();
        $client->setAuth($clientAuth['login'], $clientAuth['password']);
        $refundDescription = '';
        switch ($validated['refund_reason'])
        {
            case 'bad_quality':
                $refundDescription = 'Товар ненадлежащего качества.';
                break;
            case 'wrong_product':
                $refundDescription = 'Пришёл не тот товар.';
                break;
            case 'specs_not_met':
                $refundDescription = 'Товар не соотвествует завленным характеристикам.';
                break;
        }
        $response = $client->createRefund([
            array(
                'amount' => array(
                    'value' => $order->totalPrice,
                    'currency' => 'RUB'
                ),
                'description' => $refundDescription,
                'payment_id' => $validated['paymentId']
            )
        ], uniqid('', true));
        Refund::create([
            'payment_id' => $response->payment_id,
            'order_id' => $order->ulid,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $order->delete();
        return redirect()->route('payment.refundFinished', ['orderId' => $order->ulid]);
    }
}
