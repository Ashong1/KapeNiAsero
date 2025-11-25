<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TwoFactorController; // <--- Import the new controller
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// --- NEW SECTION: 2FA ROUTES ---
// These allow the user to see the "Enter Code" page.
// We keep these OUTSIDE the 'twofactor' group so you don't get locked out loop.
Route::middleware(['auth'])->group(function() {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
});

// --- MAIN APP ROUTES ---
// We added 'twofactor' middleware here. 
// This means: "You must be Logged In AND have entered the correct code" to see these pages.

// GROUP 1: General Access (Menu & Checkout)
Route::middleware(['auth', 'twofactor'])->group(function () {
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
});

// GROUP 2: Admin Only Routes
Route::middleware(['auth', 'twofactor', 'admin'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});