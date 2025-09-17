<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Seller\SellerAccountingReportsController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerOrdersController;
use App\Http\Controllers\Seller\SellerProductsController;

Route::middleware('banned')->group(function ()
{
    Route::controller(SellerController::class)->group(function ()
    {
        Route::get('/', 'index')->name("seller.index");
        Route::get('/account', 'account')->name("seller.account")->middleware(['auth', 'authorize.seller']);
        Route::get('/account/edit', 'editAccount')->middleware(['auth', 'authorize.seller']);
        Route::post('/account/edit', 'postEditAccount');
    });
    Route::controller(SellerAuthController::class)->group(function ()
    {
        Route::get('register', 'register');
        Route::post('register', 'postRegister');
        Route::get('login', 'login')->name('seller.login');
        Route::post('login', 'postLogin');
        Route::post('logout', 'logout');
    });
    Route::controller(SellerProductsController::class)->prefix('products')->middleware(['authorize.seller'])->group(function ()
    {
        Route::get('list', 'list')->name('seller.products.list');
        Route::get('create', 'create');
        Route::post('create', 'postProduct');
        Route::get('update', 'update')->name('seller.products.update');
        Route::patch('update', 'postUpdatedProduct');
        Route::get('delete', 'delete')->name('seller.products.delete');
        Route::post('delete', 'postDeletedProduct');
        Route::get('category/{id}/subcategories', 'categories');
        Route::get('reviews', 'reviews')->name('seller.products.reviews');
        Route::post('receive_product_reviews', 'receiveProductReviews');
        Route::post('search', 'searchProducts');
    });
    Route::controller(SellerOrdersController::class)->prefix('orders')->middleware(['auth', 'authorize.seller'])->group(function ()
    {
        Route::get('list', 'orders')->name('seller.orders.list');
        Route::post('searchOrders', 'searchProductsOrders');
    });
    Route::controller(SellerAccountingReportsController::class)->prefix('accounting_reports')->middleware(['auth', 'authorize.seller'])->group(function ()
    {
        Route::get('generic', 'genericReport');
        Route::get('payments', 'paymentsReport');
        Route::get('refunds', 'refundsReport');
        Route::get('products', 'productsReports')->name('seller.accounting_reports.products');
        Route::get('product/{product}', 'productReport')->name('seller.account_reports.product');
        Route::post('product', 'formProductReport');
        Route::get('tax', 'taxReport');
        Route::post('products/search', 'searchProducts');
    });
});

Route::fallback(FallbackController::class);
