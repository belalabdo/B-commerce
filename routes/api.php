<?php

use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistsController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\IsAdminRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware(EnsureTokenIsValid::class);
    Route::patch('/update', 'update')->middleware(EnsureTokenIsValid::class);
});

Route::controller(ProductController::class)->group(function () {
    Route::post('/products', 'create')->middleware(IsAdminRequest::class);
    Route::patch('/products/{id}', 'update')->middleware(IsAdminRequest::class);
    Route::delete('/products/{id}', 'delete')->middleware(IsAdminRequest::class);
    Route::get('/products/{id}', 'get');
    Route::get('/products', 'getAll');
});

Route::controller(CategoriesController::class)->group(function () {
    Route::get('/categories', 'getAll');
    Route::get('/categories/{id}', 'get');
    Route::get('/categories/{id}/products', 'getProducts');
    Route::post('/categories', 'create')->middleware(IsAdminRequest::class);
    Route::patch('/categories/{id}', 'update')->middleware(IsAdminRequest::class);
    Route::delete('/categories/{id}', 'delete')->middleware(IsAdminRequest::class);
});

Route::controller(BrandsController::class)->group(function () {
    Route::get('/brands', 'getAll');
    Route::get('/brands/{id}', 'get');
    Route::get('/brands/{id}/products', 'getProducts');
    Route::post('/brands', 'create')->middleware(IsAdminRequest::class);
    Route::patch('/brands/{id}', 'update')->middleware(IsAdminRequest::class);
    Route::delete('/brands/{id}', 'delete')->middleware(IsAdminRequest::class);
});

Route::controller(CartsController::class)->group(function () {
    Route::get('/cart', 'get')->middleware(EnsureTokenIsValid::class);
    Route::post('/cart', 'add')->middleware(EnsureTokenIsValid::class);
    Route::delete('/cart', 'delete')->middleware(EnsureTokenIsValid::class);
    Route::patch('/cart', 'update')->middleware(EnsureTokenIsValid::class);
});

Route::controller(WishlistsController::class)->group(function () {
    Route::get('/wishlist', 'get')->middleware(EnsureTokenIsValid::class);
    Route::post('/wishlist', 'add')->middleware(EnsureTokenIsValid::class);
    Route::delete('/wishlist', 'delete')->middleware(EnsureTokenIsValid::class);
});

Route::get('/user/{id}', function ($id) {
    $user = User::where("id", $id)->first();
    return ['tokens' => $user->tokens];
});

Route::get('/test', function () {
    $user = User::where("id", 2)->first();
    // $user->tokens()->delete();
    return ['tokens' => $user->tokens];
});
