<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerAuthController;

Route::controller(SellerController::class)->group(function(){
    Route::get('/', 'index')->name("seller.index");
    Route::get('/account', 'account')->name("seller.account");
});
Route::controller(SellerAuthController::class)->group(function(){
    Route::get('/register', 'register');
    Route::post('/register', 'postRegister');
    Route::get('/login', 'login');
    Route::post('/login', 'postLogin');
});