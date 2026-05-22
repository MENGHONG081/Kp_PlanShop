<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\AuthController;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/categories', [ProductController::class, 'categories']);
    Route::get('/products/category/{category}', [ProductController::class, 'byCategory']);

    // Auth routes (no authentication required)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);

        // Cart routes
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'add']);
        Route::delete('/cart/{product}', [CartController::class, 'remove']);
        Route::put('/cart/{product}', [CartController::class, 'update']);
        Route::post('/cart/clear', [CartController::class, 'clear']);

        // Order routes
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
    });
});
