<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ShiftController; 
use App\Http\Controllers\ParkedOrderController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\Auth\ForgotPasswordController; 

// NEW FEATURES
use App\Http\Controllers\ReportController; 
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ChangePasswordController;

// API Controllers (For Internal Use)
use App\Http\Controllers\Api\PosApiController; 
use App\Http\Controllers\Api\InventoryApiController;

Route::get('/', function () { return redirect('/login'); });

// DISABLE REGISTRATION
Auth::routes(['register' => false]);

// --- PASSWORD RESET OTP ROUTES ---
Route::get('password/otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('password/otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::post('password/otp/resend', [ForgotPasswordController::class, 'resendOtp'])->name('password.resendOtp');


// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {

    // 1. CHANGE PASSWORD ROUTES
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('password.change');
    // FIX: Renamed this route to avoid conflict with default Laravel password reset
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');

    // 2. LOGOUT ACTION
    Route::get('/logout-action', [ShiftController::class, 'handleLogout'])->name('logout.action');

    // 3. ROUTES REQUIRING MANDATORY PASSWORD CHANGE
    Route::middleware(['force.change.password', 'shift'])->group(function () {

        // --- POS API (Internal) ---
        Route::prefix('pos')->group(function () {
            Route::get('/products', [PosApiController::class, 'getProducts']);
            Route::get('/categories', [PosApiController::class, 'getCategories']);
        });

        // --- GENERAL ACCESS ---
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        
        Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
        Route::get('/orders/{order}/success', [OrderController::class, 'paymentSuccess'])->name('orders.success');
        Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
        Route::post('/orders/{order}/void-request', [OrderController::class, 'requestVoid'])->name('orders.requestVoid');

        Route::resource('shifts', ShiftController::class)->only(['index', 'create', 'store', 'edit', 'update']);

        Route::post('/park-order', [ParkedOrderController::class, 'store']);
        Route::get('/parked-orders', [ParkedOrderController::class, 'index']);
        Route::get('/parked-orders/{order}/retrieve', [ParkedOrderController::class, 'retrieve']);
        Route::delete('/parked-orders/{order}', [ParkedOrderController::class, 'destroy']);
        Route::put('/parked-orders/{order}', [ParkedOrderController::class, 'update']);

        // --- ADMIN ONLY ROUTES ---
        Route::middleware(['admin'])->group(function () {
            
            Route::prefix('internal-api')->group(function() {
                Route::put('/ingredients/{id}', [InventoryApiController::class, 'update']);
            });

            Route::resource('categories', CategoryController::class);
            Route::resource('ingredients', IngredientController::class);
            Route::resource('suppliers', SupplierController::class);
            Route::post('/ingredients/{ingredient}/restock', [IngredientController::class, 'restock'])->name('ingredients.restock');
            Route::get('/ingredients/{ingredient}/history', [IngredientController::class, 'history'])->name('ingredients.history');
            
            Route::resource('users', UserController::class); 

            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
            
            Route::post('/products/{product}/ingredient', [ProductController::class, 'addIngredient'])->name('products.addIngredient');
            Route::delete('/products/{product}/ingredient/{ingredient}', [ProductController::class, 'removeIngredient'])->name('products.removeIngredient');

            Route::post('/orders/{order}/void', [OrderController::class, 'voidOrder'])->name('orders.void');

            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        });

    });
});