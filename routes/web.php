<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\UserProfilePhotoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\DocumentationController;

/*
|--------------------------------------------------------------------------
| Web Routes - Main Router
|--------------------------------------------------------------------------
*/

// =========================================================================
// Section 1: Core & Auth
// =========================================================================

// Authentication Routes (Login, Register, Password Reset, etc.)
require __DIR__.'/auth.php';

// Authenticated General Routes
Route::middleware(['auth'])->group(function () {
    // Standard Dashboard (for users without specific roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/account/profile/photos/{photo}/activate', [UserProfilePhotoController::class, 'activate'])
        ->name('account.profile.photo.activate');
    Route::delete('/account/profile/photos/{photo}', [UserProfilePhotoController::class, 'destroy'])
        ->name('account.profile.photo.destroy');

    // Global Profile Management (if applicable)
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// =========================================================================
// Section 2: Public & API Docs
// =========================================================================

// API Documentation
Route::get('/api/documentation', [DocumentationController::class, 'index'])->name('api.documentation');
Route::get('/api/documentation.json', [DocumentationController::class, 'json'])->name('api.documentation.json');

// =========================================================================
// Section 3: Domain Routes (Role-Based Panels)
// =========================================================================

require __DIR__.'/admin.php';
require __DIR__.'/diretoria.php';
require __DIR__.'/pastor.php';
require __DIR__.'/lideres.php';
require __DIR__.'/jovens.php';

if (module_enabled('Igrejas')) {
    require module_path('Igrejas', 'routes/public.php');
}

if (module_enabled('Secretaria')) {
    require module_path('Secretaria', 'routes/public.php');
}

if (module_enabled('Calendario')) {
    require module_path('Calendario', 'routes/public.php');
}

// =========================================================================
// Section 4: Module Dynamic Routes (Theme & Extensions)
// =========================================================================

// Módulo Homepage
if (module_enabled('Homepage')) {
    $homepageRoutes = module_path('Homepage', '/routes/web.php');
    if (file_exists($homepageRoutes)) {
        require $homepageRoutes;
    }
}

// Módulo Chat
if (module_enabled('Chat')) {
    $chatRoutes = module_path('Chat', '/routes/web.php');
    if (file_exists($chatRoutes)) {
        require $chatRoutes;
    }
}
