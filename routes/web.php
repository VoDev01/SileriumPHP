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
Route::get('/catalog/product/{product}', [CatalogController::class, 'product'])->middleware('auth');
Route::get('/catalog/addtocart/{product}', [CatalogController::class, 'addToCart'])->middleware('verified');
Route::post('/catalog/postcart', [CatalogController::class, 'postCart'])->middleware('auth');

Route::get('/user/profile', [UserController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/user/editprofile', [UserController::class, 'editProfile'])->middleware('auth');
Route::post('/user/editprofile', [UserController::class, 'postEditProfile']);
Route::get('/user/login', [UserController::class, 'login'])->name('login');
Route::post('/user/postlogin', [UserController::class, 'postLogin']);
Route::get('/user/register', [UserController::class, 'register']);
Route::post('/user/postregister', [UserController::class, 'postRegister']);
Route::post('/user/logout', [UserController::class, 'logout']);

Route::get('/user/forgotpassword', [UserController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/user/forgotpassword', [UserController::class, 'postForgotPassword'])->middleware('guest')->name('password.email');
Route::get('/user/resetpassword', [UserController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/user/resetpassword', [UserController::class, 'postResetPassword'])->middleware('guest')->name('password.update');

Route::get('/user/email/verify', [UserController::class, 'verifyEmail']);
Route::get('/user/email/verify/{id}/{hash}', [UserController::class, 'emailVerificationHandler'])->middleware(['auth', 'signed'])->name('varification.verify');
Route::post('/user/email/resend-verification', [UserController::class, 'resendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/user/reviews', [UserController::class, 'userReviews'])->middleware(['auth', 'verified'])->name('userReviews');
Route::get('/user/review/product/{product}', [UserController::class, 'review'])->middleware(['auth', 'verified']);
Route::post('/user/postreview', [UserController::class, 'postReview']);
Route::get('/user/review/editreview/{review}', [UserController::class, 'editReview']);
Route::post('/user/posteditreview', [UserController::class, 'postEditReview']);
Route::post('/user/deletereview', [UserController::class, 'deleteReview']);

Route::get('/user/cart', [UserController::class, 'cart'])->middleware(['auth', 'verified'])->name('cart');
Route::post('/user/cart/changeamount', [UserController::class, 'changeAmount'])->middleware('auth');
Route::post('/user/cart/removefromcart', [UserController::class, 'removeFromCart'])->middleware('auth');

Route::get('/user/orders/all', [UserController::class, 'allOrders'])->middleware(['auth', 'verified']);
Route::get('/user/orders/editorder/{order}', [UserController::class, 'editOrder']);
Route::post('/user/orders/posteditorder', [UserController::class, 'postEditOrder']);
Route::post('/user/orders/closeorder', [UserController::class, 'closeOrder']);
Route::get('/user/orders/ordershistory', [UserController::class, 'ordersHistory']);
Route::post('/user/orders/filtershopcart', [UserController::class, 'filterShopCart']);