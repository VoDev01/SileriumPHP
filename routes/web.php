<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index']);

Route::get('/categories/all', [CategoriesController::class, 'index']);
Route::get('/categories/{category}/subcategories', [CategoriesController::class, 'subcategories']);

Route::get('/catalog/products', [CatalogController::class, 'products'])->name('allproducts');
Route::post('/catalog/filter', [CatalogController::class, 'filterProducts']);
Route::get('/catalog/product/{product}', [CatalogController::class, 'product']);
Route::get('/catalog/addtocart/{product}', [CatalogController::class, 'addToCart']);
Route::get('/catalog/postcart/{product}', [CatalogController::class, 'postCart']);

Route::get('/user/profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');
Route::get('/user/login', [UserController::class, 'login'])->name('login');
Route::post('/user/postlogin', [UserController::class, 'postLogin']);
Route::get('/user/register', [UserController::class, 'register']);
Route::post('/user/postregister', [UserController::class, 'postRegister']);