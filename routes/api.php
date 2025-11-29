<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Order;

// 1. PUBLIC ROUTES (No login required)
// Perfect for: Digital Menu Boards, Self-Service Kiosks
Route::get('/menu', function () {
    // Return all products with stock > 0, including their category
    // We select specific fields to make the response smaller/faster
    return Product::where('stock', '>', 0)
        ->select('id', 'name', 'price', 'image', 'stock')
        ->get();
});

// 2. PROTECTED ROUTES (Login required via Token)
// Perfect for: Waiter Apps, Kitchen Display Systems, Inventory Managers
Route::middleware('auth:sanctum')->group(function () {
    
    // Get the logged-in user (Waiter/Admin)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Create a new order from a mobile device
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
    
    // Kitchen Display: Fetch only "pending" orders
    Route::get('/kitchen/orders', function () {
        return Order::with('items.product')
            ->where('status', 'pending')
            ->oldest() // First in, First out
            ->get();
    });
});