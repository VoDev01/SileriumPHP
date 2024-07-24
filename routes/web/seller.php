<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\SellerController;

Route::controller(SellerController::class)->group(function(){
    Route::get('/', 'index');
});