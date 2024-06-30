<?php

use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\UsersAdminPanelController;
use App\Http\Controllers\Admin\ProductsAdminPanelController;

Route::middleware('authorize.admin')->group(function () {

    Route::get('home', [AdminPanelController::class, 'home'])->name('admin_home');
    Route::get('profile', [AdminPanelController::class, 'profile']);

    Route::controller(ProductsAdminPanelController::class)->group(function () {
        Route::get('products/index', 'products_index')->name('products_index');
        Route::get('products/create', 'create_product');
        Route::post('products/post_product', 'post_product');
        Route::get('products/update/{product}', 'update_product');
        Route::post('products/post_updated_product', 'post_updated_product');
        Route::get('products/delete/{product}', 'delete_product');
        Route::post('products/post_deleted_product', 'post_deleted_product');
        Route::get('category/{id}/subcategories', 'categories');
        Route::get('product/{id}/{name?}', 'product');
    });
    Route::controller(UsersAdminPanelController::class)->group(function () {
        Route::get('users/index', 'index')->name('users_index');
    });

});