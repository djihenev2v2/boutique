<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\WilayaController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CatalogController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ClientOrderController;
use App\Http\Controllers\Client\ClientProfileController;
use App\Http\Controllers\Client\FavoritesController;
use App\Http\Controllers\Client\PageController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Route racine — landing page publique ou redirection
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
    return view('landing');
})->name('landing');

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

    // Pages statiques
    Route::get('/conditions-de-vente', [PageController::class, 'termsOfSale'])->name('terms');

    // Favoris
    Route::get('/favoris', [FavoritesController::class, 'index'])->name('favoris.index');
    Route::post('/favoris/toggle', [FavoritesController::class, 'toggle'])->name('favoris.toggle');

    // Profil client
    Route::get('/mon-profil', [ClientProfileController::class, 'edit'])->name('client.profile');
    Route::put('/mon-profil', [ClientProfileController::class, 'update'])->name('client.profile.update');
    Route::put('/mon-profil/mot-de-passe', [ClientProfileController::class, 'updatePassword'])->name('client.profile.password');
});

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

    // Clients (2.8 / 2.9)
    Route::get('clients', [ClientAdminController::class, 'index'])->name('clients.index');
    Route::get('clients/{user}', [ClientAdminController::class, 'show'])->name('clients.show');

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
