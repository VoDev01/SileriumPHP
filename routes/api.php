<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIAuthController;
use App\Http\Controllers\API\V1\APIHomeController;
use App\Http\Controllers\API\V1\APIUsersController;
use App\Http\Controllers\API\V1\APIProfileController;
use App\Http\Controllers\API\V1\APIReviewsController;
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

Route::withoutMiddleware(['api'])->middleware(['web'])->group(function ()
{
    Route::get('/', [APIHomeController::class, 'index']);

    Route::middleware(['auth'])->group(function ()
    {
        Route::controller(APIProfileController::class)->group(function ()
        {
            Route::post('secret/refresh', 'refreshToken');
            Route::post('secret', 'generateToken');
            Route::get('profile', 'profile')->name('api.profile');
            Route::post('logout', 'logout');
        });
    });
});

Route::middleware(['auth:api', 'auth'])->group(function ()
{

    Route::controller(APIProductsController::class)->prefix('products')->group(function ()
    {
        Route::get('index/{itemsPerPage?}', 'index');
        Route::get('show/{product}', 'show');
        Route::post('create', 'create')->middleware('authorize.seller');
        Route::patch('update', 'update')->middleware('authorize.seller.admin');
        Route::delete('delete', 'delete')->middleware('authorize.seller.admin');
        Route::post('by_name_seller', 'productsByNameSeller');
    });
    Route::controller(APISubcategoriesController::class)->prefix('subcategories')->group(function ()
    {
        Route::get('index/{itemsPerPage?}', 'index');
        Route::get('show/{subcategory}', 'show');
        Route::post('create', 'create')->middleware('authorize.admin');
        Route::patch('update', 'update')->middleware('authorize.admin');
        Route::delete('delete', 'delete')->middleware('authorize.admin');
    });
    Route::controller(APIReviewsController::class)->prefix('reviews')->group(function ()
    {
        Route::get('index', 'index')->middleware('web');
        Route::patch('update', 'update');
        Route::delete('delete', 'delete');
        Route::post('search_user_reviews', 'searchUserReviews')->middleware('authorize.seller.admin');
        Route::post('search_product_reviews', 'searchProductReviews')->middleware('authorize.seller.admin');
    });
    Route::controller(APIUsersController::class)->prefix('user')->group(function ()
    {
        Route::post('search', 'search')->middleware('authorize.admin');
    });
});
