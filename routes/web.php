<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController; // <--- Added this line
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// GROUP 1: Public Routes (Logged in Employees & Admins)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    
    // Allow everyone to SEE the list
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Allow everyone to CHECKOUT (Process Sales)
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
});

// GROUP 2: Admin Only Routes (The "Separate Interface")
Route::middleware(['auth', 'admin'])->group(function () {
    // Only admins can access Create, Store, Edit, Update, Destroy
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});