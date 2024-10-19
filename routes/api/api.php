<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIHomeController;
use App\Http\Controllers\API\V1\APIUsersController;
use App\Http\Controllers\API\V1\APIProductsController;
use App\Http\Controllers\API\V1\APISubcategoriesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('home', [APIHomeController::class, 'index']);

Route::controller(APIProductsController::class)->prefix('product')->group(function(){
    Route::get('index/{itemsPerPage?}', 'index');
    Route::get('show/{product}', 'show');
    Route::post('create', 'create');
    Route::put('update', 'update');
    Route::delete('delete', 'delete');
    Route::get('name_seller/{sellerNickname}/{productName}/{loadWith?}', 'productsByNameSeller');
});
Route::controller(APISubcategoriesController::class)->prefix('subcategories')->group(function(){
    Route::get('index/{itemsPerPage?}', 'index');
    Route::get('show/{subcategory}', 'show');
    Route::post('create', 'create');
    Route::put('update', 'update');
    Route::delete('delete', 'delete');
});
Route::controller(APIUsersController::class)->prefix('user')->group(function(){
    Route::get('find/{email}/{loadWith?}/{name?}/{surname?}/{id?}/{phone?}', 'find');
});
