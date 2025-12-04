<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PosApiController;
use App\Http\Controllers\Api\InventoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Public Routes ---

// POS: Get Products (for frontend/kiosk)
Route::get('/pos/products', [PosApiController::class, 'getProducts']);


// --- Protected Routes (Requires Bearer Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
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
    });

});