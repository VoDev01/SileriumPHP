<?php

use App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function(){
    Route::get('profile', 'profile')->name('profile')->middleware('auth');
    Route::get('editprofile', 'editProfile')->middleware('auth');
    Route::post('editprofile', 'postEditProfile');
    Route::get('login', 'login')->name('login');
    Route::post('postlogin', 'postLogin');
    Route::get('register', 'register');
    Route::post('postregister', 'postRegister');
    Route::post('logout', 'logout');
    
    Route::get('forgotpassword', 'forgotPassword')->middleware('guest')->name('password.request');
    Route::post('forgotpassword', 'postForgotPassword')->middleware('guest')->name('password.email');
    Route::get('resetpassword', 'resetPassword')->middleware('guest')->name('password.reset');
    Route::post('resetpassword', 'postResetPassword')->middleware('guest')->name('password.update');
    
    Route::get('email/verify', 'verifyEmail');
    Route::get('email/verify/{id}/{hash}', 'emailVerificationHandler')->middleware(['auth', 'signed'])->name('varification.verify');
    Route::post('email/resend-verification', 'resendEmailVerification')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    
    Route::get('reviews', 'userReviews')->middleware(['auth', 'verified'])->name('userReviews');
    Route::get('review/product/{product}', 'review')->middleware(['auth', 'verified']);
    Route::post('postreview', 'postReview');
    Route::get('review/editreview/{review}', 'editReview');
    Route::post('posteditreview', 'postEditReview');
    Route::post('deletereview', 'deleteReview');
    
    Route::get('cart', 'cart')->middleware(['auth', 'verified'])->name('cart');
    Route::post('cart/changeamount', 'changeAmount')->middleware('auth');
    Route::post('cart/removefromcart', 'removeFromCart')->middleware('auth');
    
    Route::get('orders/all', 'allOrders')->middleware(['auth', 'verified']);
    Route::get('orders/editorder/{order}', 'editOrder');
    Route::post('orders/posteditorder', 'postEditOrder');
    Route::post('orders/closeorder', 'closeOrder');
    Route::get('orders/ordershistory', 'ordersHistory');
    Route::post('orders/filtershopcart', 'filterShopCart');
});