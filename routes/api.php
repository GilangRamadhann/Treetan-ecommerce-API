<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

// Webhook (tidak pakai access.key & auth)
Route::post('/webhooks/xendit', [WebhookController::class, 'handleXendit'])
    ->name('webhooks.xendit');

// Auth (register, login) pakai access key
Route::middleware('access.key')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        // Products
        Route::get('/products',           [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);

        // Checkout
        Route::post('/checkout', [CheckoutController::class, 'store']);

        // Payment (kalau dipakai terpisah)
        Route::post('/payment/{order}', [PaymentController::class, 'pay']);

        // Riwayat Pesanan
        Route::get('/orders',         [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
    });
});
