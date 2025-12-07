<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;          // <--- Added AuthController
use App\Http\Controllers\Api\PosApiController;
use App\Http\Controllers\Api\InventoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ========================================================================
// PUBLIC ROUTES (No Token Required)
// ========================================================================

// 1. Authentication (Login to get the Bearer Token)
Route::post('/login', [AuthController::class, 'login']);

// 2. POS: Public Data for Frontend/Kiosk
Route::get('/pos/products', [PosApiController::class, 'getProducts']);
Route::get('/pos/categories', [PosApiController::class, 'getCategories']); // Added based on Controller availability


// ========================================================================
// PROTECTED ROUTES (Requires 'Bearer Token' in Headers)
// ========================================================================
Route::middleware('auth:sanctum')->group(function () {
    
    // --- Authentication ---
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- User Info ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- POS System (Cashier/User Actions) ---
    Route::prefix('pos')->group(function () {
        Route::post('/order', [PosApiController::class, 'placeOrder']);      // Place a new order
        Route::get('/my-orders', [PosApiController::class, 'getMyOrders']);  // View cashier's recent orders
    });

    // --- Inventory Management API ---
    Route::prefix('inventory')->group(function () {
        
        // 1. List all ingredients
        Route::get('/ingredients', [InventoryApiController::class, 'index']);
        
        // 2. Create new ingredient
        Route::post('/ingredients', [InventoryApiController::class, 'store']);
        
        // 3. Get single ingredient details
        Route::get('/ingredients/{id}', [InventoryApiController::class, 'show']);
        
        // 4. Update ingredient details (or manual stock adjust)
        Route::put('/ingredients/{id}', [InventoryApiController::class, 'update']);
        
        // 5. Restock endpoint (Stock In)
        Route::post('/ingredients/{id}/restock', [InventoryApiController::class, 'restock']);
        
        // 6. View history logs (Stock Card)
        Route::get('/ingredients/{id}/history', [InventoryApiController::class, 'history']);
        
        // 7. Delete ingredient (Optional)
        Route::delete('/ingredients/{id}', [InventoryApiController::class, 'destroy']);
    });

});