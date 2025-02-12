<?php

namespace App\Services;

use Carbon\Carbon;
use YooKassa\Client;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Actions\DisplayPaymentCancellationMessage;

class PaymentService
{
    public static function create(Request $request, array $clientAuth)
    {
        $validated = $request->validated();
        $order = Order::with(['user', 'products'])->where('ulid', $validated['orderId'])->get()->first();
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
                    'type' => 'embedded',
                ],
                'capture' => false,
                'description' => 'Заказ ' . $order->ulid,
                'metadata' => [
                    'order_id' => $order->ulid
                ]
            ],
            $idempotenceKey,
        );
        if ($response->status == 'succeeded' || $response->status == 'pending')
        {
            $confirmationToken = $response->getConfirmation()->getConfirmationToken();
            Payment::create([
                'payment_id' => $response->id,
                'user_id' => Auth::user()->ulid,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'status' => $response->status
            ]);
            return redirect()->route('payment.sendConfirmationToken', ['confirmationToken' => $confirmationToken, 'orderId' => $order->ulid]);
        }
        else if ($response->status == 'canceled')
        {
            PaymentService::cancel($request);
            return redirect()->route('payment.cancelled')->with('cancellationMessage', DisplayPaymentCancellationMessage::display(
                $response->cancellation_details->party,
                $response->cancellation_details->reason
            ));
        }
    }
    public static function cancel(Request $request)
    {
        if (isset($request->paymentId))
        {
            Payment::where('payment_id', $request->paymentId)->update(['status' => 'cancelled']);
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
        $response = $client->createRefund([
            array(
                'amount' => array(
                    'value' => $order->totalPrice,
                    'currency' => 'RUB'
                ),
                'payment_id' => $validated['paymentId']
            )
        ], uniqid('', true));
        Refund::create([
            'payment_id' => $response->payment_id,
            'user_id' => $order->user->ulid,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $order->delete();
        return redirect()->route('payment.refundFinished');
    }
}