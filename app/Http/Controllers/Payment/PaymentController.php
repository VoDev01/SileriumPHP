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
use App\Services\PaymentService;
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
        return PaymentService::create($request, ['login' => '1026235', 'password' => 'test_dfoZBZwwXDoC1gXWqeg_wNhYFsAqv-dU91hezVx04Y0']);
    }
    public function receiveConfirmationToken(Request $request)
    {
        return redirect('/payment/credentials')->with('confirmationToken', $request->confirmationToken)->with('orderId', $request->orderId);
    }
    public function finished(Request $request)
    {
        $order = Order::where('ulid', $request->orderId)->get()->first();
        return view('payment.finished', ['order' => $order]);
    }
    public function cancelled(Request $request)
    {
        $cancellationMessage = session('cancellationMessage');
        PaymentService::cancel($request);
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
