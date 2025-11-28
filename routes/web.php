<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () { return redirect('/login'); });

Auth::routes();

// 2FA Routes
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
});

// ADMIN ONLY
Route::middleware(['auth', 'twofactor', 'admin'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::post('/products/{product}/ingredient', [ProductController::class, 'addIngredient'])->name('products.addIngredient');
    Route::delete('/products/{product}/ingredient/{ingredient}', [ProductController::class, 'removeIngredient'])->name('products.removeIngredient');
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    
    // Existing route to FINALIZE/APPROVE the void (Admin only)
    Route::post('/orders/{order}/void', [App\Http\Controllers\OrderController::class, 'voidOrder'])->name('orders.void');
});