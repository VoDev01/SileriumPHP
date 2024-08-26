<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\UsersAdminPanelController;
use App\Http\Controllers\Admin\ProductsAdminPanelController;

Route::middleware('authorize.admin')->group(function () {

    Route::get('/', [AdminPanelController::class, 'index'])->name('admin_index');
    Route::get('profile', [AdminPanelController::class, 'profile']);

    Route::controller(ProductsAdminPanelController::class)->prefix('products')->group(function () {
        Route::get('index', 'products_index')->name('index');
        Route::get('create', 'create');
        Route::post('post_product', 'postProduct');
        Route::get('update/{product}', 'update');
        Route::put('post_updated_product', 'postUpdatedProduct');
        Route::get('delete/{product}', 'delete');
        Route::delete('post_deleted_product', 'postDeletedProduct');
        Route::get('category/{id}/subcategories', 'categories');
        Route::get('{id}/{name?}', 'product');
    });
    Route::controller(UsersAdminPanelController::class)->prefix('users')->group(function () {
        Route::get('index', 'index')->name('users_index');
        Route::get('roles', 'roles')->name('user_roles');
        Route::get('orders', 'orders')->name('user_orders');
        Route::get('reviews', 'reviews')->name('user_reviews');
        Route::get('find/{load_with}/{email}/{redirect?}/{name?}/{surname?}/{id?}/{phone?}', 'findUsers')->name('find_user');
        Route::post('post_user_search', 'postUserSearch');
    });

});