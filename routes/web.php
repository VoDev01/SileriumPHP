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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories/all', [CategoriesController::class, 'index']);
Route::get('/categories/{category}/subcategories', [CategoriesController::class, 'subcategories']);

Route::get('/catalog/products/{sortOrder?}/{available?}/{subcategory?}/{product?}', [CatalogController::class, 'products'])->name('allproducts');
Route::post('/catalog/rubcurrency', [CatalogController::class, 'rubCurrency']);
Route::post('/catalog/dolcurrency', [CatalogController::class, 'dolCurrency']);
Route::post('/catalog/filter', [CatalogController::class, 'filterProducts']);
Route::get('/catalog/product/{product}', [CatalogController::class, 'product']);
Route::get('/catalog/addtocart/{product}', [CatalogController::class, 'addToCart']);
Route::post('/catalog/postcart', [CatalogController::class, 'postCart'])->middleware('auth');

Route::get('/user/profile', [UserController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/user/editprofile', [UserController::class, 'editProfile'])->middleware('auth');
Route::post('/user/posteditprofile', [UserController::class, 'postEditProfile']);
Route::get('/user/login', [UserController::class, 'login'])->name('login');
Route::post('/user/postlogin', [UserController::class, 'postLogin']);
Route::get('/user/register', [UserController::class, 'register']);
Route::post('/user/postregister', [UserController::class, 'postRegister']);
Route::post('/user/logout', [UserController::class, 'logout']);

Route::get('/user/shopcart', [UserController::class, 'shopCart'])->middleware('auth')->name('shopcart');
Route::get('/user/editorder/{order}', [UserController::class, 'editOrder']);
Route::post('/user/posteditorder', [UserController::class, 'postEditOrder']);
Route::post('/user/closeorder', [UserController::class, 'closeOrder']);
Route::get('/user/ordershistory', [UserController::class, 'ordersHistory']);
Route::post('/user/filtershopcart', [UserController::class, 'filterShopCart']);