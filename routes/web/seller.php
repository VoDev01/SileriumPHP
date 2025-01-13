<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Seller\SellerAccountingReport;
use App\Http\Controllers\Seller\SellerAccountingReportPDF;
use App\Http\Controllers\Seller\SellerAccountingReports;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerOrdersController;
use App\Http\Controllers\Seller\SellerProductsController;

Route::middleware('banned')->group(function ()
{
    Route::controller(SellerController::class)->group(function ()
    {
        Route::get('/', 'index')->name("seller.index");
        Route::get('/account', 'account')->name("seller.account")->middleware('auth');
    });
    Route::controller(SellerAuthController::class)->group(function ()
    {
        Route::get('register', 'register');
        Route::post('register', 'postRegister');
        Route::get('login', 'login')->name('seller.login');
        Route::post('login', 'postLogin');
        Route::post('logout', 'logout');
    });
    Route::controller(SellerProductsController::class)->prefix('products')->middleware('auth')->group(function ()
    {
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
        Route::post('search', 'searchProducts');
    });
    Route::controller(SellerOrdersController::class)->prefix('orders')->middleware('auth')->group(function ()
    {
        Route::get('list', 'orders')->name('seller.orders.list');
        Route::post('searchOrders', 'searchProductsOrders');
    });
    Route::controller(SellerAccountingReports::class)->prefix('accounting_reports')->middleware('auth')->group(function () {
        Route::get('index', 'index');
        Route::get('generic_report', 'genericReport');
        Route::get('product_report', 'productReport');
        Route::get('tax_report', 'taxReport');
    });
    Route::controller(SellerAccountingReportPDF::class)->prefix('accounting_reports/pdf')->middleware('auth')->group(function (){
        Route::post('format', 'format');
    });
});

Route::fallback(FallbackController::class);
