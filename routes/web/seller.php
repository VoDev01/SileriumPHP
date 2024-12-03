<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerProductsController;

Route::controller(SellerController::class)->group(function(){
    Route::get('/', 'index')->name("seller.index");
    Route::get('/account', 'account')->name("seller.account")->middleware('auth');
});
Route::controller(SellerAuthController::class)->group(function(){
    Route::get('register', 'register');
    Route::post('register', 'postRegister');
    Route::get('login', 'login')->name('seller.login');
    Route::post('login', 'postLogin');
    Route::post('logout', 'logout');
});
Route::controller(SellerProductsController::class)->prefix('products')->middleware('auth')->group(function(){
    Route::get('index', 'index')->name('seller.products.index');
    Route::get('list', 'list')->name('seller.products.list');
    Route::get('create', 'create');
    Route::post('create', 'postProduct');
    Route::get('update', 'update')->name('seller.products.update');
    Route::post('update', 'postUpdatedProduct');
    Route::get('delete', 'delete')->name('seller.products.delete');
    Route::post('delete', 'postDeletedProduct');
    Route::get('category/{id}/subcategories', 'categories');
    Route::get('reviews', 'reviews')->name('seller.products.reviews');
    Route::post('receive_product_reviews', 'receiveProductReviews');
});
Route::controller(SellerProductsController::class)->prefix('products')->group(function(){
    Route::post('search', 'searchProducts');
});