<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\UsersAdminPanelController;
use App\Http\Controllers\Admin\ProductsAdminPanelController;

Route::middleware(['authorize.admin', 'banned'])->group(function ()
{

    Route::get('index', [AdminPanelController::class, 'index'])->name('admin.index');
    Route::get('profile', [AdminPanelController::class, 'profile']);

    Route::controller(ProductsAdminPanelController::class)->prefix('products')->group(function ()
    {
        Route::get('index', 'index')->name('admin.products.index');
        Route::get('update', 'update')->name('admin.products.update');
        Route::post('update', 'postUpdatedProduct');
        Route::get('delete', 'delete')->name('admin.products.delete');
        Route::post('deletet', 'postDeletedProduct');
        Route::get('category/{id}/subcategories', 'categories');
        Route::post('search', 'searchProducts');
        Route::get('reviews', 'reviews')->name('admin.products.reviews');
        Route::post('receive_product_reviews', 'receiveProductReviews');
    });
    Route::controller(UsersAdminPanelController::class)->prefix('users')->group(function ()
    {
        Route::get('index', 'index')->name('admin.users.index');
        Route::get('roles', 'roles')->name('admin.users.roles');
        Route::get('orders', 'orders')->name('admin.users.orders');
        Route::post('orders', 'searchUserOrders');
        Route::get('reviews', 'reviews')->name('admin.users.reviews');
        Route::post('reviews', 'searchUserReviews');
        Route::get('ban', 'ban')->name("admin.users.ban");
        Route::post('ban', 'postBan');
        Route::post('search', 'searchUsers')->name('admin.users.search');
    });

    Route::post('/token', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'token',
        'middleware' => 'throttle',
    ]);

    Route::get('/authorize', [
        'uses' => 'AuthorizationController@authorize',
        'as' => 'authorizations.authorize',
        'middleware' => 'web',
    ]);

    $guard = config('passport.guard', null);

    Route::middleware(['web', $guard ? 'auth:' . $guard : 'auth'])->group(function ()
    {
        Route::post('/token/refresh', [
            'uses' => 'TransientTokenController@refresh',
            'as' => 'token.refresh',
        ]);

        Route::post('/authorize', [
            'uses' => 'ApproveAuthorizationController@approve',
            'as' => 'authorizations.approve',
        ]);

        Route::delete('/authorize', [
            'uses' => 'DenyAuthorizationController@deny',
            'as' => 'authorizations.deny',
        ]);

        Route::get('/tokens', [
            'uses' => 'AuthorizedAccessTokenController@forUser',
            'as' => 'tokens.index',
        ]);

        Route::delete('/tokens/{token_id}', [
            'uses' => 'AuthorizedAccessTokenController@destroy',
            'as' => 'tokens.destroy',
        ]);

        Route::get('/clients', [
            'uses' => 'ClientController@forUser',
            'as' => 'clients.index',
        ]);

        Route::post('/clients', [
            'uses' => 'ClientController@store',
            'as' => 'clients.store',
        ]);

        Route::put('/clients/{client_id}', [
            'uses' => 'ClientController@update',
            'as' => 'clients.update',
        ]);

        Route::delete('/clients/{client_id}', [
            'uses' => 'ClientController@destroy',
            'as' => 'clients.destroy',
        ]);

        Route::get('/scopes', [
            'uses' => 'ScopeController@all',
            'as' => 'scopes.index',
        ]);

        Route::get('/personal-access-tokens', [
            'uses' => 'PersonalAccessTokenController@forUser',
            'as' => 'personal.tokens.index',
        ]);

        Route::post('/personal-access-tokens', [
            'uses' => 'PersonalAccessTokenController@store',
            'as' => 'personal.tokens.store',
        ]);

        Route::delete('/personal-access-tokens/{token_id}', [
            'uses' => 'PersonalAccessTokenController@destroy',
            'as' => 'personal.tokens.destroy',
        ]);
    });
});

Route::fallback(FallbackController::class);