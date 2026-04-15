<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\AdminController;

/*
| Rotas do *módulo* Admin (CRUD exemplo) — prefixo dedicado para não colidir com routes/admin.php (admin.*).
*/
Route::middleware(['web', 'auth', 'verified'])->prefix('admin-module')->name('admin-module.')->group(function () {
    Route::resource('admins', AdminController::class);
});
