<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories/all', [CategoriesController::class, 'index']);
Route::get('/categories/{category}/subcategories', [CategoriesController::class, 'subcategories']);

Route::controller(CatalogController::class)->prefix('catalog')->group(function(){
    Route::get('products/{sortOrder?}/{available?}/{subcategory?}/{product?}', 'products')->name('allproducts');
    Route::post('rubcurrency', 'rubCurrency');
    Route::post('dolcurrency','dolCurrency');
    Route::post('filter','filterProducts');
    Route::get('product/{product}', 'product')->middleware('auth');
    Route::get('addtocart/{product}','addToCart')->middleware('verified');
    Route::post('postcart', 'postCart')->middleware('auth');
});

Route::fallback(FallbackController::class);