<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerAuthController;

Route::controller(SellerController::class)->group(function(){
    Route::get('/', 'index');
});
Route::controller(SellerAuthController::class)->group(function(){
    Route::get('/register', 'register');
    Route::post('/post_register', 'post_register');
});