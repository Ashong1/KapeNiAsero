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

// GENERAL ACCESS (With 2FA) - Employees and Admins
Route::middleware(['auth', 'twofactor'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
    Route::post('/orders/{order}/void-request', [OrderController::class, 'requestVoid'])->name('orders.requestVoid');

    // --- SHIFT MANAGEMENT ROUTES ---
    // 1. The resource route for Create, Store, Edit, Update
    Route::resource('shifts', ShiftController::class)->only(['create', 'store', 'edit', 'update']);
    
    // 2. The MISSING route that caused your error (Smart Logout)
    Route::get('/logout-action', [ShiftController::class, 'handleLogout'])->name('logout.action');

    // --- PARKED ORDERS ROUTES ---
    Route::post('/park-order', [ParkedOrderController::class, 'store']);
    Route::get('/parked-orders', [ParkedOrderController::class, 'index']);
    Route::get('/parked-orders/{order}/retrieve', [ParkedOrderController::class, 'retrieve']);
    Route::delete('/parked-orders/{order}', [ParkedOrderController::class, 'destroy']);
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