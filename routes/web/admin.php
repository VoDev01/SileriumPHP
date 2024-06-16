<?php

use App\Http\Controllers\AdminPanelController;

Route::controller(AdminPanelController::class)->group(function(){
    Route::get('home', 'home')->name('admin-home');
});