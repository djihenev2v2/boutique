<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CatalogController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ClientOrderController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Route racine — redirige vers login ou dashboard
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
    return redirect()->route('login');
});

// ============================================================
// Routes authentification (guest uniquement)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Déconnexion (authentifié)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================================
// Redirection intelligente post-login
// ============================================================
Route::middleware(['auth', 'no-cache'])->get('/home', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('client.home');
})->name('home');

// ============================================================
// Routes Client (authentifié)
// ============================================================
Route::middleware(['auth', 'no-cache'])->group(function () {
    // Catalogue
    Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalogue');
    Route::get('/produit/{slug}', [CatalogController::class, 'show'])->name('product.show');

    // Panier
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/panier/mettre-a-jour', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/supprimer/{variantId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/panier/promo', [CartController::class, 'applyPromo'])->name('cart.promo');
    Route::delete('/panier/promo', [CartController::class, 'removePromo'])->name('cart.promo.remove');

    // Checkout
    Route::get('/commander', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/commander', [CheckoutController::class, 'store'])->name('checkout.store');

    // Mes commandes
    Route::get('/mes-commandes', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/mes-commandes/{orderNumber}', [ClientOrderController::class, 'show'])->name('orders.show');
    Route::get('/commande-confirmee/{orderNumber}', [ClientOrderController::class, 'confirmation'])->name('orders.confirmation');
});

// ============================================================
// Routes Admin (authentifié + rôle admin)
// ============================================================
Route::middleware(['auth', 'admin', 'no-cache'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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
});
