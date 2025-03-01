<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\CatalogController;
use App\Http\Controllers\Product\CategoriesController;

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
Route::get('/categories/all', [CategoriesController::class, 'index']);
Route::get('/categories/{category}/subcategories', [CategoriesController::class, 'subcategories']);

Route::controller(CatalogController::class)->prefix('catalog')->group(function(){
    Route::get('products/{sortOrder?}/{available?}/{subcategory?}/{name?}', 'products')->name('allproducts');
    Route::post('rub_currency', 'rubCurrency');
    Route::post('dol_currency','dolCurrency');
    Route::post('filter','filterProducts');
    Route::get('product/{productId}', 'product')->middleware('auth');
    Route::post('/products/search', 'searchPorducts');
});