<?php

namespace App\Http\Controllers\Payment;

use App\Actions\DisplayPaymentCancellationMessage;
use YooKassa\Client;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Payment\PaymentRequest;
use Carbon\Carbon;

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
        if($response->status == 'succeeded')
        {
            $confirmationToken = $response->getConfirmation()->getConfirmationToken();
            Payment::create([
                'payment_id' => $response->id,
                'user_id' => Auth::user()->ulid,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return redirect()->route('payment.sendConfirmationToken', ['confirmationToken' => $confirmationToken, 'orderId' => $order->ulid]);
        }
        else if($response->status == 'canceled')
        {
            $order = Order::find($validated['orderId']);
            $order->delete();
            return redirect()->route('payment.cancelled')->with('cancellationMessage', DisplayPaymentCancellationMessage::display(
                $response->cancellation_details->party,
                $response->cancellation_details->reason
            ));
        }
        else
        {
            return redirect()->route('payment.confirm');
        }
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
    public function cancelled()
    {
        $cancellationMessage = session('cancellationMessage');
        return view('payment.cancelled', [
            'cancellationParty' => $cancellationMessage['cancellationParty'], 
            'cancellationReason' => $cancellationMessage['cancellationReason']
        ]);
    }
    public function confirmation()
    {
        return view('payment.confirmation');
    }
}
