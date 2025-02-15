<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\RefundController;

Route::controller(PaymentController::class)->group(function(){
    Route::get('receive_order_id', 'receiveOrderId')->name('payment.receiveOrderId');
    Route::get('create_payment', 'createPayment')->name('payment.createPayment');
    Route::post('create_payment_request', 'createPaymentRequest')->name('payment.createPaymentRequest');
    Route::get('finished/{orderId}', 'finished');
    Route::post('on_status_changed', 'onStatusChanged');
});
Route::controller(RefundController::class)->group(function(){
    Route::get('listRefunds', 'listRefunds');
    Route::post('refund', 'refund');
    Route::get('refundFinished', 'refundFinished');
});