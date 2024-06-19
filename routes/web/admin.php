<?php

use App\Http\Controllers\AdminPanelController;

Route::controller(AdminPanelController::class)->group(function(){
    Route::get('home', 'home')->name('admin-home');

    Route::get('products/index', 'products_index')->name('products_index_view');
    Route::get('products/create', 'create_prodct');
    Route::post('products/post_product', 'post_product');
    Route::get('products/update/{product}', 'update_product');
    Route::post('products/post_updated_product', 'post_updated_product');
    Route::get('products/delete_product/{product}', 'delete_product');
    Route::post('products/post_deleted_product', 'post_deleted_product');
});