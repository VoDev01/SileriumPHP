<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::controller(PaymentController::class)->group(function(){
    Route::get('credentials', 'credentialsForm');
    Route::get('send_confirmation_token', 'sendConfirmationToken')->name('payment.sendConfirmationToken');
    Route::get('receive_order_id', 'receiveOrderId')->name('payment.receiveOrderId');
    Route::post('receive_confirmation_token', 'receiveConfirmationToken');
    Route::post('create_payment_request', 'createPaymentRequest')->name('payment.createPaymentRequest');
    Route::get('finished', 'finished');
});