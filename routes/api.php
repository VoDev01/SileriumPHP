<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIHomeController;
use App\Http\Controllers\API\V1\APIUsersController;
use App\Http\Controllers\API\V1\APIProductsController;
use App\Http\Controllers\API\V1\APIReviewsController;
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


Route::middleware(['api', 'banned', 'authorize.api.loggedin', 'authorize.api.key'])->prefix('/api/v1')->group(function ()
{
    Route::get('home', [APIHomeController::class, 'index']);

    Route::controller(APIProductsController::class)->prefix('products')->group(function ()
    {
        Route::get('index/{itemsPerPage?}', 'index');
        Route::get('show/{product}', 'show');
        Route::post('create', 'create')->middleware('authorize.seller.api');
        Route::put('update', 'update')->middleware('authorize.seller.admin.api');
        Route::delete('delete', 'delete')->middleware('authorize.seller.admin.api');
        Route::post('by_name_seller', 'productsByNameSeller');
    });
    Route::controller(APISubcategoriesController::class)->prefix('subcategories')->group(function ()
    {
        Route::get('index/{itemsPerPage?}', 'index');
        Route::get('show/{subcategory}', 'show');
        Route::post('create', 'create')->middleware('authorize.admin.api');
        Route::put('update', 'update')->middleware('authorize.admin.api');
        Route::delete('delete', 'delete')->middleware('authorize.admin.api');
    });
    Route::controller(APIReviewsController::class)->prefix('reviews')->group(function ()
    {
        Route::get('index', 'index')->middleware('web');
        Route::put('update', 'update');
        Route::delete('delete', 'delete');
        Route::post('search_user_reviews', 'searchUserReviews')->middleware('authorize.seller.admin.api');
        Route::post('search_product_reviews', 'searchProductReviews')->middleware('authorize.seller.admin.api');
    });
    Route::controller(APIUsersController::class)->prefix('user')->group(function ()
    {
        Route::post('search', 'search')->middleware('authorize.admin.api');
    });
});