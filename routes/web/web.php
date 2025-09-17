<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannedController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Formatting\PdfFormatterController;

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

Route::get('/', [HomeController::class, 'index'])->middleware(['banned'])->name('home');

Route::get('/banned', [BannedController::class, 'banned'])->name('banned');//->middleware('banned');

Route::controller(PdfFormatterController::class)->prefix('format')->middleware(['auth', 'banned'])->group(function ()
{
    Route::post('pdf', 'formatPDF');
});

Route::fallback(FallbackController::class);
