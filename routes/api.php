<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

// --- PUBLIC ROUTES (Anyone can see these) ---
Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);

// --- SECURE ROUTES (Requires Token) ---
// Only authorized apps (with a valid token) can check stock levels
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products/{id}/stock', [ProductApiController::class, 'checkStock']);
    
    // Route to get current user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});