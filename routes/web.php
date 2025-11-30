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
use App\Http\Controllers\TwoFactorController;

// NEW FEATURES
use App\Http\Controllers\ReportController; 
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;

Route::get('/', function () { return redirect('/login'); });

Auth::routes();

// --- 2FA VERIFICATION ROUTES ---
Route::middleware(['auth'])->group(function() {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
});

// --- GENERAL ACCESS (Employees & Admins) ---
// Requires Login + 2FA
Route::middleware(['auth', 'twofactor'])->group(function () {
    
    // Dashboard & POS
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Transaction Logic
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
    Route::get('/orders/{order}/success', [OrderController::class, 'paymentSuccess'])->name('orders.success');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
    
    // Void Request (Employee side)
    Route::post('/orders/{order}/void-request', [OrderController::class, 'requestVoid'])->name('orders.requestVoid');

    // Shift Management 
    // 'index' is for Admins (History), others are for Employees (Actions)
    Route::resource('shifts', ShiftController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::get('/logout-action', [ShiftController::class, 'handleLogout'])->name('logout.action');

    // Parked Orders
    Route::post('/park-order', [ParkedOrderController::class, 'store']);
    Route::get('/parked-orders', [ParkedOrderController::class, 'index']);
    Route::get('/parked-orders/{order}/retrieve', [ParkedOrderController::class, 'retrieve']);
    Route::delete('/parked-orders/{order}', [ParkedOrderController::class, 'destroy']);
});

// --- ADMIN ONLY ROUTES ---
Route::middleware(['auth', 'twofactor', 'admin'])->group(function () {
    
    // Master Data Management
    Route::resource('categories', CategoryController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('suppliers', SupplierController::class);
    
    // Product Management (Full Access)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Product Ingredients (Recipe)
    Route::post('/products/{product}/ingredient', [ProductController::class, 'addIngredient'])->name('products.addIngredient');
    Route::delete('/products/{product}/ingredient/{ingredient}', [ProductController::class, 'removeIngredient'])->name('products.removeIngredient');

    // Admin Actions
    Route::post('/orders/{order}/void', [OrderController::class, 'voidOrder'])->name('orders.void');

    // --- NEW FEATURES ---

    // 1. Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // 2. Audit Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // 3. System Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});