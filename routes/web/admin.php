<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\UsersAdminPanelController;
use App\Http\Controllers\Admin\ProductsAdminPanelController;

Route::middleware(['authorize.admin', 'banned'])->group(function ()
{
    Route::controller(AdminPanelController::class)->group(function ()
    {
        Route::get('index', 'index')->name('admin.index');
        Route::get('profile', 'profile');
        Route::post('logout', 'logout');
        Route::get('documentation/{url?}', 'documentation')->where('url', '(.*)');
        Route::get('coverage/{url?}', 'coverage')->where('url', '(.*)');
        Route::get('control_auth_dropbox', 'controlAuthDropbox');
        Route::post('revoke_dropbox_token', 'revokeDropboxToken');
        Route::get('receive_dropbox_token', 'receiveDropboxToken');
    });

    Route::controller(ProductsAdminPanelController::class)->prefix('products')->group(function ()
    {
        Route::get('index', 'index')->name('admin.products.index');
        Route::get('update', 'update')->name('admin.products.update');
        Route::patch('update', 'postUpdatedProduct');
        Route::get('delete', 'delete')->name('admin.products.delete');
        Route::delete('delete', 'postDeletedProduct');
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
        Route::get('payments', 'payments')->name('admin.users.payments');
        Route::post('payments/search', 'searchPayments')->name('admin.payments.search');
        Route::post('search', 'searchUsers')->name('admin.users.search');
    });
});

Route::fallback(FallbackController::class);
