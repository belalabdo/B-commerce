<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\IsAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;
use function Pest\Laravel\json;


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

Route::get('/user/{id}', function ($id) {
    $user = User::where("id", $id)->first();
    return ['tokens' => $user->tokens];
});

Route::get('/test', function () {
    $user = User::where("id", 2)->first();
    // $user->tokens()->delete();
    return ['tokens' => $user->tokens];
});
