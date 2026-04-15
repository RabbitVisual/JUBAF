<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// Guest Routes (Unauthenticated)
// =========================================================================

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Brute-force protection for login
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');

    // Demo: mesmo critério que a secção na view de login (nunca em produção)
    if (! app()->environment('production')) {
        /**
         * @warning Login automático sem credenciais — apenas fora de produção.
         */
        Route::get('/auto-login/{type}', [LoginController::class, 'autoLogin'])->name('auto-login');
    }

    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password Recovery
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    // Password Reset
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// =========================================================================
// Authenticated Routes
// =========================================================================

Route::middleware('auth')->group(function () {

    // Logout (POST is the secure standard)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Optional: Keep GET logout as fallback with deprecation warning or simple redirect
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');
});
