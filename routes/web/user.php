<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserCartController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserReviewController;


Route::middleware(['banned', 'authorize.user.routes'])->group(function ()
{
    Route::controller(UserController::class)->group(function ()
    {
        Route::get('profile', 'profile')->name('profile')->middleware('auth');
        Route::get('edit_profile', 'editProfile')->middleware('auth');
        Route::post('edit_profile', 'postEditProfile');
        Route::post('logout', 'logout');
    });
    Route::controller(UserAuthController::class)->withoutMiddleware('authorize.user.routes')->group(function ()
    {
        Route::get('login/{api?}', 'login')->name('login');
        Route::post('login', 'postLogin');
        Route::get('register', 'register');
        Route::post('register', 'postRegister');

        Route::get('forgot_password', 'forgotPassword')->middleware('guest')->name('password.request');
        Route::post('forgot_password', 'postForgotPassword')->middleware('guest')->name('password.email');
        Route::get('reset_password', 'resetPassword')->middleware('guest')->name('password.reset');
        Route::post('reset_password', 'postResetPassword')->middleware('guest')->name('password.update');
        Route::get('email/verify', 'verifyEmail')->middleware('auth')->name('verification.notice');
        Route::get('email/verify/{id}/{hash}', 'emailVerificationHandler')->middleware(['auth', 'signed'])->name('verification.verify');
        Route::post('email/resend_verification', 'resendEmailVerification')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
        Route::get('phone/verify', 'verifyPhone')->middleware(['auth'])->name('phone_verification');
        Route::post('phone/resend_verification', 'resendPhoneVerification')->middleware(['auth'])->name('phone_verification.send');
        Route::post('phone/validate_verification', 'validatePhoneVerification')->middleware(['atuh'])->name('phone_verification.validate');
    });
    Route::controller(UserReviewController::class)->group(function ()
    {
        Route::get('reviews', 'userReviews')->middleware(['auth', 'verified'])->name('userReviews');
        Route::get('review/product/{productId}', 'review')->middleware(['auth', /*'verified'*/]);
        Route::post('review', 'postReview');
        Route::get('review/edit_review/{review}', 'editReview');
        Route::patch('edit_review', 'postEditReview');
        Route::delete('delete_review', 'deleteReview');
    });
    Route::controller(UserOrderController::class)->prefix('orders')->group(function ()
    {
        Route::get('all', 'allOrders')->middleware(['auth', /*'verified'*/]);
        Route::get('edit_order/{order}', 'editOrder');
        Route::patch('edit_order', 'postEditOrder');
        Route::delete('close_order', 'closeOrder');
        Route::get('history', 'ordersHistory');
        Route::post('filter_shop_cart', 'filterShopCart');
        Route::post('checkout_order', 'checkoutOrder');
        Route::get('refund', 'refund');
    });
    Route::controller(UserCartController::class)->prefix('cart')->group(function ()
    {
        Route::get('/', 'cart')->middleware(['auth', /*'verified'*/])->name('cart');
        Route::get('add_to_cart/{productId}', 'addToCart');//->middleware('verified');
        Route::post('add_to_cart', 'postCart')->middleware('auth');
        Route::post('change_amount', 'changeAmount')->middleware('auth');
        Route::post('remove_from_cart', 'removeFromCart')->middleware('auth');
    });
});
Route::fallback(FallbackController::class);
