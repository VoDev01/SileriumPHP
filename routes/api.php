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

Route::withoutMiddleware(['api', 'auth.refresh.token'])->middleware(['web'])->group(function ()
{
    Route::get('/', [APIHomeController::class, 'index'])->withoutMiddleware(['auth:api']);

    Route::controller(APIProfileController::class)->group(function ()
    {
        Route::post('secret/refresh', 'refreshToken');
        Route::post('secret', 'generateToken');
        Route::get('profile', 'profile')->name('api.profile');
        Route::post('logout', 'logout');
    });
});

Route::controller(APIProductsController::class)->prefix('products')->group(function ()
{
    Route::get('index/{itemsPerPage?}', 'index');
    Route::get('show/{id}', 'show');
    Route::post('create', 'create');
    Route::patch('update', 'update');
    Route::delete('delete', 'delete');
    Route::post('search', 'search');
    Route::post('profit_between_date', 'profitBetweenDate');
    Route::post('consumption_between_date', 'consumptionBetweenDate');
    Route::post('est_amount_expiry', 'amountExpiry');
});
Route::controller(APISubcategoriesController::class)->prefix('subcategories')->group(function ()
{
    Route::get('index/{itemsPerPage?}', 'index');
    Route::get('show/{subcategory}', 'show');
    Route::post('create', 'create');
    Route::patch('update', 'update');
    Route::delete('delete', 'delete');
});
Route::controller(APIReviewsController::class)->prefix('reviews')->group(function ()
{
    Route::get('index/{itemsPerPage?}', 'index');
    Route::patch('update', 'update');
    Route::delete('delete', 'delete');
    Route::post('search_user_reviews', 'searchUserReviews');
    Route::post('search_product_reviews', 'searchProductReviews');
    Route::post('average_rating', 'averageRating');
    Route::post('rating_count', 'ratingCount');
});
Route::controller(APIUsersController::class)->prefix('user')->group(function ()
{
    Route::post('search', 'search');
});
