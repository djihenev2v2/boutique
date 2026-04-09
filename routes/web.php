<?php

use App\Http\Controllers\Auth\AuthController;
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
// Routes Admin (authentifié + rôle admin)
// ============================================================
Route::middleware(['auth', 'admin', 'no-cache'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});
