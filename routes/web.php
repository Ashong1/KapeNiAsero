<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ParkedOrderController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () { return redirect('/login'); });

Auth::routes();

// 2FA Verification Routes
Route::middleware(['auth'])->group(function() {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
});

// GENERAL ACCESS (Employees & Admins)
// WE ADD 'shift' HERE so it runs on all these pages
Route::middleware(['auth', 'twofactor', 'shift'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // --- SHIFT MANAGEMENT ---
    // These must be accessible, which is why we added them to $excludedRoutes in the Middleware
    Route::get('/register/open', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('/register/open', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('/register/close/{shift}', [ShiftController::class, 'edit'])->name('shifts.close');
    Route::put('/register/close/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::get('/logout-action', [ShiftController::class, 'handleLogout'])->name('logout.action');

    // --- POS & ORDER ROUTES (These will now be blocked if no shift is open) ---
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
    Route::post('/orders/{order}/void-request', [OrderController::class, 'requestVoid'])->name('orders.requestVoid');

    // --- PARKED ORDERS ROUTES ---
    Route::post('/park-order', [ParkedOrderController::class, 'store']);
    Route::get('/parked-orders', [ParkedOrderController::class, 'index']);
    Route::get('/parked-orders/{order}/retrieve', [ParkedOrderController::class, 'retrieve']);
    Route::delete('/parked-orders/{order}', [ParkedOrderController::class, 'destroy']);
});

// ADMIN ONLY ROUTES
Route::middleware(['auth', 'twofactor', 'admin'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    
    // Shift History Report
    Route::resource('shifts', ShiftController::class)->only(['index']);
    
    // Product Management
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/ingredient', [ProductController::class, 'addIngredient'])->name('products.addIngredient');
    Route::delete('/products/{product}/ingredient/{ingredient}', [ProductController::class, 'removeIngredient'])->name('products.removeIngredient');

    // Admin Actions
    Route::post('/orders/{order}/void', [App\Http\Controllers\OrderController::class, 'voidOrder'])->name('orders.void');
});