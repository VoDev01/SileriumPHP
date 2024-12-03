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

Route::get('home', [APIHomeController::class, 'index'])->middleware('authorize.api');

Route::controller(APIProductsController::class)->prefix('products')->middleware('authorize.api')->group(function ()
{
    Route::get('index/{itemsPerPage?}', 'index');
    Route::get('show/{product}', 'show');
    Route::post('create', 'create')->withoutMiddleware('authorize.api')->middleware('authorize.seller.api');
    Route::put('update', 'update')->withoutMiddleware('authorize.api')->middleware('authorize.seller.admin.api');
    Route::delete('delete', 'delete')->withoutMiddleware('authorize.api')->middleware('authorize.seller.admin.api');
    Route::post('by_name_seller', 'productsByNameSeller');
});
Route::controller(APISubcategoriesController::class)->prefix('subcategories')->middleware('authorize.api')->group(function ()
{
    Route::get('index/{itemsPerPage?}', 'index');
    Route::get('show/{subcategory}', 'show');
    Route::post('create', 'create')->withoutMiddleware('authorize.api')->middleware('authorize.admin.api');
    Route::put('update', 'update')->withoutMiddleware('authorize.api')->middleware('authorize.admin.api');
    Route::delete('delete', 'delete')->withoutMiddleware('authorize.api')->middleware('authorize.admin.api');
});
Route::controller(APIReviewsController::class)->prefix('reviews')->middleware('authorize.api')->group(function ()
{
    Route::get('index', 'index')->middleware('web');
    Route::put('update', 'update');
    Route::delete('delete', 'delete');
    Route::post('search_user_reviews', 'searchUserReviews')->withoutMiddleware('authorize.api')->middleware('authorize.seller.admin.api');
    Route::post('search_product_reviews', 'searchProductReviews')->withoutMiddleware('authorize.api')->middleware('authorize.seller.admin.api');
});
Route::controller(APIUsersController::class)->prefix('user')->middleware('authorize.api')->group(function ()
{
    Route::post('search', 'search');
});
