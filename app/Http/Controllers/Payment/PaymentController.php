<?php

namespace App\Http\Controllers\Payment;

use App\Models\Order;
use App\Models\Refund;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\PaymentService;
use App\Events\Product\ProductBoughtEvent;
use App\Http\Requests\Payment\PaymentRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentController extends Controller
{
    public function createPayment()
    {
        return view('payment.createPayment');
    }
    public function createPaymentRequest()
    {
        return PaymentService::create(['login' => '1026235', 'password' => 'test_dfoZBZwwXDoC1gXWqeg_wNhYFsAqv-dU91hezVx04Y0']);
    }
    public function finished(Request $request)
    {
        $order = Order::with('products')->where('ulid', $request->orderId)->get()->first();
        foreach ($order->products as $product)
        {
            ProductBoughtEvent::dispatch($product, $product->pivot->productAmount);
        }
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
    public function onStatusChanged()
    {
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);

        $factory = new \YooKassa\Model\Notification\NotificationFactory();
        $notificationObject = $factory->factory($requestBody);
        $responseObject = $notificationObject->getObject();

        $client = new \YooKassa\Client();

        try
        {
            if (!$client->isNotificationIPTrusted($_SERVER['REMOTE_ADDR']))
            {
                throw new HttpException(400, 'Что то пошло не так');
            }

            if ($notificationObject->getEvent() === \YooKassa\Model\Notification\NotificationEventType::PAYMENT_SUCCEEDED)
            {
                $responseData = [
                    'paymentId' => $responseObject->getId(),
                    'paymentStatus' => $responseObject->getStatus(),
                ];
                Payment::where('payment_id', $responseData['paymentId'])->update(['status' => $responseData['paymentStatus']]);
            }
            elseif ($notificationObject->getEvent() === \YooKassa\Model\Notification\NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE)
            {
                $responseData = [
                    'paymentId' => $responseObject->getId(),
                    'paymentStatus' => $responseObject->getStatus(),
                ];
                Payment::where('payment_id', $responseData['paymentId'])->update(['status' => $responseData['paymentStatus']]);
            }
            elseif ($notificationObject->getEvent() === \YooKassa\Model\Notification\NotificationEventType::PAYMENT_CANCELED)
            {
                $responseData = [
                    'paymentId' => $responseObject->getId(),
                    'paymentStatus' => $responseObject->getStatus(),
                ];
                Payment::where('payment_id', $responseData['paymentId'])->update(['status' => $responseData['paymentStatus']]);
            }
            elseif ($notificationObject->getEvent() === \YooKassa\Model\Notification\NotificationEventType::REFUND_SUCCEEDED)
            {
                $responseData = [
                    'refundId' => $responseObject->getId(),
                    'refundStatus' => $responseObject->getStatus(),
                    'paymentId' => $responseObject->getPaymentId(),
                ];
                Refund::where('payment_id', $responseData['paymentId'])->update(['status' => $responseData['refundStatus']]);
            }
            else
            {
                throw new HttpException(400, 'Что то пошло не так');
            }
        }
        catch (HttpException $e)
        {
            return response($e->getMessage(), $e->getStatusCode());
        }

        return response();
    }
}
