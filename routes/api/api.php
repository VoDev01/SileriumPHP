<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIHomeController;
use App\Http\Controllers\API\V1\APIUsersController;
use App\Http\Controllers\API\V1\APIProductsController;

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

Route::controller(APIProductsController::class)->group(function(){
    Route::get('products/index/{items_per_page}/', 'index');
});
Route::controller(APIUsersController::class)->group(function(){
    Route::get('users/find/{load_with}/{email}/{name?}/{surname?}/{id?}/{phone?}', 'find');
});
