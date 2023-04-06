<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Routes Auth
Auth::routes();
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class], 'logout');

//Routes Home Page
Route::get('home', [App\Http\Controllers\visitorController::class, 'index'])->name('home');
Route::get('cart', [App\Http\Controllers\visitorController::class, 'cart'])->name('cart');
Route::get('add-to-cart/{id}', [App\Http\Controllers\visitorController::class, 'addToCart'])->name('add.to.cart');
Route::patch('update-cart', [App\Http\Controllers\visitorController::class, 'update'])->name('update.cart');
Route::delete('remove-from-cart', [App\Http\Controllers\visitorController::class, 'remove'])->name('remove.from.cart');
Route::get('product-detail/{id}', [App\Http\Controllers\visitorController::class, 'productDetail'])->name('productDetail');
Route::post('reply/{id}', [App\Http\Controllers\visitorController::class, 'storeReplies']);
Route::get('products', [App\Http\Controllers\visitorController::class, 'products'])->name('products');
Route::get('product/{id}', [App\Http\Controllers\visitorController::class, 'product'])->name('product');
Route::get('orderControl', [App\Http\Controllers\visitorController::class, 'orderControl'])->name('orderControl');
Route::get('checkCoupon', [App\Http\Controllers\visitorController::class, 'checkCoupon'])->name('checkCoupon');
Route::get('feedbacks', [App\Http\Controllers\VisitorController::class, 'feedbacks'])->name('feedbacks');
Route::get('edit-profile', [App\Http\Controllers\VisitorController::class, 'editProfile'])->name('edit.profile');
Route::post('edit-profile', [App\Http\Controllers\VisitorController::class, 'updateProfile']);
Route::get('orderHistory/{filter}', [App\Http\Controllers\visitorController::class, 'orderHistory'])->name('orderHistory');
Route::get('orderDetail/{id}', [App\Http\Controllers\visitorController::class, 'orderDetail'])->name('orderDetail');
Route::get('orderCancel/{id}/{filter}', [App\Http\Controllers\visitorController::class, 'orderCancel'])->name('orderCancel');
Route::get('review/{id}', [App\Http\Controllers\visitorController::class, 'reviewProduct'])->name('reviewProduct');
Route::get('submitReview', [App\Http\Controllers\visitorController::class, 'submitReview'])->name('submitReview');

//Routes Admin
Route::get('admin/overview', [App\Http\Controllers\adminController::class, 'overview'])->name('admin.overview');
Route::get('admin/user-tables', [App\Http\Controllers\adminController::class, 'userTables'])->name('admin.user-tables');
Route::get('admin/product-tables', [App\Http\Controllers\adminController::class, 'productTables'])->name('admin.product-tables');
Route::get('admin/oder-tables', [App\Http\Controllers\adminController::class, 'oderTables'])->name('admin.oder-tables');
Route::get('admin/feedback-tables', [App\Http\Controllers\adminController::class, 'feedbackTables'])->name('admin.feedback-tables');
