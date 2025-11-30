<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShiftController; // <--- DON'T FORGET THIS IMPORT
use Illuminate\Support\Facades\Auth;

Route::get('/', function () { return redirect('/login'); });

Auth::routes();

// 2FA Verification Routes (Must NOT use 'twofactor' middleware)
Route::middleware(['auth'])->group(function() {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
});

// Protected Routes (Requires Login + 2FA)
Route::middleware(['auth', 'twofactor'])->group(function () {
    // Dashboard & Orders
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
    Route::post('/orders/{order}/void-request', [OrderController::class, 'requestVoid'])->name('orders.requestVoid');

    // --- SHIFT MANAGEMENT (The part you added) ---
    Route::get('/shift/open', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('/shift/open', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('/shift/{shift}/close', [ShiftController::class, 'edit'])->name('shifts.close');
    Route::put('/shift/{shift}', [ShiftController::class, 'update'])->name('shifts.update');

    Route::get('/logout-action', [ShiftController::class, 'handleLogout'])->name('logout.action');
});

// Admin Only Routes
Route::middleware(['auth', 'twofactor', 'admin'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    
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