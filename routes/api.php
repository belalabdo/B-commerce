<?php

use App\Http\Controllers\AddressesController;
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
    Route::post('/products', 'create')->middleware('isAdmin');
    Route::patch('/products/{id}', 'update')->middleware('isAdmin');
    Route::delete('/products/{id}', 'delete')->middleware('isAdmin');
    Route::get('/products/{id}', 'get');
    Route::get('/products', 'getAll');
});

Route::controller(CategoriesController::class)->group(function () {
    Route::get('/categories', 'getAll');
    Route::get('/categories/{id}', 'get');
    Route::get('/categories/{id}/products', 'getProducts');
    Route::post('/categories', 'create')->middleware('isAdmin');
    Route::patch('/categories/{id}', 'update')->middleware('isAdmin');
    Route::delete('/categories/{id}', 'delete')->middleware('isAdmin');
});

Route::controller(BrandsController::class)->group(function () {
    Route::get('/brands', 'getAll');
    Route::get('/brands/{id}', 'get');
    Route::get('/brands/{id}/products', 'getProducts');
    Route::post('/brands', 'create')->middleware('isAdmin');
    Route::patch('/brands/{id}', 'update')->middleware('isAdmin');
    Route::delete('/brands/{id}', 'delete')->middleware('isAdmin');
});

Route::controller(CartsController::class)
    ->middleware(EnsureTokenIsValid::class)
    ->group(function () {
        Route::get('/cart', 'get');
        Route::post('/cart', 'add');
        Route::delete('/cart', 'delete');
        Route::patch('/cart', 'update');
    });

Route::controller(WishlistsController::class)
    ->middleware(EnsureTokenIsValid::class)
    ->group(function () {
        Route::get('/wishlist', 'get');
        Route::post('/wishlist', 'add');
        Route::delete('/wishlist', 'delete');
    });

Route::controller(AddressesController::class)
    ->middleware(EnsureTokenIsValid::class)
    ->group(function () {
        Route::get('/addresses', 'getAll');
        Route::get('/addresses/{id}', 'get');
        Route::post('/addresses', 'add');
        Route::patch('/addresses/{id}', 'update');
        Route::delete('/addresses/{id}', 'delete');
    });


// testing endpoints
Route::get('/user/{id}', function ($id) {
    $user = User::where("id", $id)->first();
    return ['tokens' => $user->tokens];
});

Route::get('/test', function () {
    $user = User::where("id", 12)->first();
    // $user->tokens()->delete();
    return $user->remember_token;
    // return ['tokens' => $user->tokens];
});
