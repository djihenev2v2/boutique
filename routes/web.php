<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\WilayaController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Route racine — landing page publique
// ============================================================
Route::get('/', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('landing');
})->name('landing');

// ============================================================
// Admin login (guest uniquement)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/admin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin', [AuthController::class, 'login']);
});

// Déconnexion (authentifié)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================================
// Routes Admin (authentifié + rôle admin)
// ============================================================
Route::middleware(['auth', 'admin', 'no-cache'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])
        ->name('products.toggle-active');

    // Categories (2.7)
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Orders (2.5 / 2.6)
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('orders/{order}/shipment', [OrderController::class, 'updateShipment'])->name('orders.updateShipment');
    Route::patch('orders/{order}/note', [OrderController::class, 'updateNote'])->name('orders.updateNote');

    // Settings (2.12)
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Livraison - Wilayas (2.10)
    Route::get('livraison', [WilayaController::class, 'index'])->name('livraison.index');
    Route::post('livraison/save', [WilayaController::class, 'saveAll'])->name('livraison.save');
    Route::post('livraison/bulk', [WilayaController::class, 'bulkUpdate'])->name('livraison.bulk');

    // Marketing - Codes promo (2.11)
    Route::get('marketing', [PromoCodeController::class, 'index'])->name('marketing.index');
    Route::post('marketing', [PromoCodeController::class, 'store'])->name('marketing.store');
    Route::put('marketing/{promoCode}', [PromoCodeController::class, 'update'])->name('marketing.update');
    Route::delete('marketing/{promoCode}', [PromoCodeController::class, 'destroy'])->name('marketing.destroy');
    Route::patch('marketing/{promoCode}/toggle', [PromoCodeController::class, 'toggle'])->name('marketing.toggle');
});
