<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannedController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Product\CatalogController;
use App\Http\Controllers\Product\CategoriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/banned', [BannedController::class, 'banned'])->name('banned')->middleware('banned');

Route::fallback(FallbackController::class);