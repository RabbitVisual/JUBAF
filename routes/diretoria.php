<?php

use Illuminate\Support\Facades\Route;
use Modules\Avisos\App\Http\Controllers\Admin\AvisosAdminController;
use Modules\Homepage\App\Http\Controllers\Admin\HomepageAdminController;
use Modules\Notificacoes\App\Http\Controllers\Admin\NotificacoesAdminController;
use Modules\PainelDiretoria\App\Http\Controllers\BoardMemberController;
use Modules\PainelDiretoria\App\Http\Controllers\DevotionalController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaCarouselController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaDashboardController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaModuleController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaPermissionController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaProfileController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaRoleController;
use Modules\PainelDiretoria\App\Http\Controllers\DiretoriaUserController;
use Modules\PainelDiretoria\App\Http\Controllers\ProfileDataChangeRequestController;
use Modules\PainelDiretoria\App\Http\Middleware\SetBibleAdminDiretoriaContext;
use Modules\Permisao\App\Http\Controllers\AccessHubController;

/*
|--------------------------------------------------------------------------
| Painel Diretoria (JUBAF) — URLs canónicas /diretoria, nomes diretoria.*
|--------------------------------------------------------------------------
*/

Route::any('/co-admin/{path?}', function (?string $path = null) {
    $target = '/diretoria';
    if ($path !== null && $path !== '') {
        $target .= '/'.$path;
    }

    return redirect($target, 301);
})->where('path', '.*');

Route::prefix('diretoria')->name('diretoria.')->middleware(['auth', 'diretoria.panel'])->group(function () {

    Route::get('/', [DiretoriaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DiretoriaDashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/profile', [DiretoriaProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [DiretoriaProfileController::class, 'update'])->name('profile.update');

    if (module_enabled('Homepage')) {
        Route::resource('board-members', BoardMemberController::class)->except(['show']);
        Route::post('devotionals/fetch-scripture', [DevotionalController::class, 'fetchScripture'])->name('devotionals.fetch-scripture');
        Route::get('devotionals/bible-books', [DevotionalController::class, 'bibleBooks'])->name('devotionals.bible-books');
        Route::get('devotionals/bible-chapters', [DevotionalController::class, 'bibleChapters'])->name('devotionals.bible-chapters');
        Route::resource('devotionals', DevotionalController::class)->except(['show']);

        Route::middleware(['can:homepage.edit'])->group(function () {
            Route::get('/homepage', [HomepageAdminController::class, 'index'])->name('homepage.index');
            Route::put('/homepage', [HomepageAdminController::class, 'update'])->name('homepage.update');
            Route::post('/homepage/toggle-section', [HomepageAdminController::class, 'toggleSection'])->name('homepage.toggle-section');
            Route::get('/homepage/contacts', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageContactAdminController::class, 'index'])->name('homepage.contacts.index');
            Route::get('/homepage/contacts/{id}', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageContactAdminController::class, 'show'])->name('homepage.contacts.show')->whereNumber('id');
            Route::delete('/homepage/contacts/{id}', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageContactAdminController::class, 'destroy'])->name('homepage.contacts.destroy')->whereNumber('id');
            Route::get('/homepage/newsletter', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'index'])->name('homepage.newsletter.index');
            Route::get('/homepage/newsletter/compose', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'create'])->name('homepage.newsletter.create');
            Route::post('/homepage/newsletter/send', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'send'])->name('homepage.newsletter.send');
            Route::delete('/homepage/newsletter/{id}', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'destroy'])->name('homepage.newsletter.destroy')->whereNumber('id');
        });
    }

    if (module_enabled('Notificacoes')) {
        Route::prefix('notificacoes')->name('notificacoes.')->group(function () {
            Route::get('/', [NotificacoesAdminController::class, 'index'])->name('index');
            Route::get('/create', [NotificacoesAdminController::class, 'create'])->name('create');
            Route::post('/', [NotificacoesAdminController::class, 'store'])->name('store');
            Route::post('/mark-all-read', [NotificacoesAdminController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::post('/{id}/read', [NotificacoesAdminController::class, 'markAsRead'])->name('markAsRead')->whereNumber('id');
            Route::get('/{notificaco}', [NotificacoesAdminController::class, 'show'])->name('show')->whereNumber('notificaco');
            Route::delete('/{notificaco}', [NotificacoesAdminController::class, 'destroy'])->name('destroy')->whereNumber('notificaco');
        });
    }

    if (module_enabled('Avisos')) {
        Route::prefix('avisos')->name('avisos.')->group(function () {
            Route::get('/', [AvisosAdminController::class, 'index'])->name('index');
            Route::get('/create', [AvisosAdminController::class, 'create'])->name('create');
            Route::post('/', [AvisosAdminController::class, 'store'])->name('store');
            Route::get('/{aviso}', [AvisosAdminController::class, 'show'])->name('show');
            Route::get('/{aviso}/edit', [AvisosAdminController::class, 'edit'])->name('edit');
            Route::put('/{aviso}', [AvisosAdminController::class, 'update'])->name('update');
            Route::delete('/{aviso}', [AvisosAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{aviso}/toggle-ativo', [AvisosAdminController::class, 'toggleAtivo'])->name('toggle-ativo');
        });
    }

    if (module_enabled('Blog')) {
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::prefix('categorias')->name('categories.')->group(function () {
                Route::get('/', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'index'])->name('index');
                Route::get('/create', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'create'])->name('create');
                Route::post('/', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\Modules\Blog\App\Http\Controllers\Admin\BlogCategoriesAdminController::class, 'toggleStatus'])->name('toggle-status');
            });

            Route::prefix('tags')->name('tags.')->group(function () {
                Route::get('/', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'index'])->name('index');
                Route::get('/create', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'create'])->name('create');
                Route::post('/', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'show'])->name('show')->whereNumber('id');
                Route::get('/{id}/edit', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'destroy'])->name('destroy');
                Route::post('/clean-unused', [\Modules\Blog\App\Http\Controllers\Admin\BlogTagsAdminController::class, 'cleanUnused'])->name('clean-unused');
            });

            Route::prefix('comentarios')->name('comments.')->group(function () {
                Route::get('/', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'index'])->name('index');
                Route::get('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'show'])->name('show');
                Route::post('/{id}/aprovar', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'approve'])->name('approve');
                Route::post('/{id}/rejeitar', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'reject'])->name('reject');
                Route::delete('/{id}', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'destroy'])->name('destroy');
                Route::post('/bulk/aprovar', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'bulkApprove'])->name('bulk.approve');
                Route::post('/bulk/rejeitar', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'bulkReject'])->name('bulk.reject');
                Route::delete('/bulk/excluir', [\Modules\Blog\App\Http\Controllers\Admin\BlogCommentsAdminController::class, 'bulkDelete'])->name('bulk.delete');
            });

            Route::post('/generate-monthly-report', [\Modules\Blog\App\Http\Controllers\BlogIntegrationController::class, 'generateMonthlyReport'])->name('generate-monthly-report');
            Route::post('/upload-image', [\Modules\Blog\App\Http\Controllers\Admin\BlogAdminController::class, 'uploadEditorImage'])->name('upload-image');
            Route::post('/redact-image', [\Modules\Blog\App\Http\Controllers\Admin\BlogAdminController::class, 'redactImage'])->name('redact-image');

            Route::resource('/', \Modules\Blog\App\Http\Controllers\Admin\BlogAdminController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ])->parameters(['' => 'blog']);
        });
    }

    Route::middleware(['diretoria.executive'])->group(function () {
        Route::get('/seguranca', [AccessHubController::class, 'diretoria'])->name('seguranca.hub');

        Route::resource('carousel', DiretoriaCarouselController::class);
        Route::post('carousel/toggle', [DiretoriaCarouselController::class, 'toggle'])->name('carousel.toggle');
        Route::post('carousel/reorder', [DiretoriaCarouselController::class, 'reorder'])->name('carousel.reorder');

        Route::resource('users', DiretoriaUserController::class);
        Route::post('users/{id}/toggle-status', [DiretoriaUserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::get('/profile-data-requests', [ProfileDataChangeRequestController::class, 'index'])->name('profile-data-requests.index');
        Route::post('/profile-data-requests/{profile_sensitive_data_request}/approve', [ProfileDataChangeRequestController::class, 'approve'])->name('profile-data-requests.approve');
        Route::post('/profile-data-requests/{profile_sensitive_data_request}/reject', [ProfileDataChangeRequestController::class, 'reject'])->name('profile-data-requests.reject');

        Route::resource('roles', DiretoriaRoleController::class);
        Route::get('permissions', [DiretoriaPermissionController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [DiretoriaPermissionController::class, 'store'])->name('permissions.store');
        Route::get('permissions/{permission}/edit', [DiretoriaPermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('permissions/{permission}', [DiretoriaPermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{permission}', [DiretoriaPermissionController::class, 'destroy'])->name('permissions.destroy');

        Route::get('modules', [DiretoriaModuleController::class, 'index'])->name('modules.index');
        Route::get('modules/{moduleName}', [DiretoriaModuleController::class, 'show'])->name('modules.show');
        Route::post('modules/{moduleName}/enable', [DiretoriaModuleController::class, 'enable'])->name('modules.enable');
        Route::post('modules/{moduleName}/disable', [DiretoriaModuleController::class, 'disable'])->name('modules.disable');
    });

    if (module_enabled('Bible')) {
        Route::middleware(['diretoria.executive', SetBibleAdminDiretoriaContext::class])
            ->prefix('biblia-digital')
            ->name('bible.')
            ->group(function () {
                require module_path('Bible', 'routes/admin-superadmin.php');
            });
    }

    if (module_enabled('Igrejas')) {
        require module_path('Igrejas', 'routes/diretoria.php');
    }

    if (module_enabled('Secretaria')) {
        require module_path('Secretaria', 'routes/diretoria.php');
    }

    if (module_enabled('Financeiro')) {
        require module_path('Financeiro', 'routes/diretoria.php');
    }

    if (module_enabled('Gateway')) {
        require module_path('Gateway', 'routes/diretoria.php');
    }

    if (module_enabled('Calendario')) {
        require module_path('Calendario', 'routes/diretoria.php');
    }

    if (module_enabled('Talentos')) {
        require module_path('Talentos', 'routes/diretoria.php');
    }

    if (module_enabled('Chat')) {
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'index'])->name('index');
            Route::get('/realtime', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'realtime'])->name('realtime');
            Route::get('/statistics', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'statistics'])->name('statistics');
            Route::get('/{id}', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'show'])->name('show')->where('id', '[0-9]+');
            Route::post('/{id}/assign', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'assign'])->name('assign');
            Route::post('/{id}/transfer', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'transfer'])->name('transfer');
            Route::post('/{id}/close', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'close'])->name('close');
            Route::post('/{id}/reopen', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'reopen'])->name('reopen');
            Route::get('/api/sessions', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiGetSessions'])->name('api.sessions');
            Route::get('/{id}/api/messages', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiGetMessages'])->name('api.messages');
            Route::post('/{id}/api/message', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiSendMessage'])->name('api.message');
            Route::post('/{id}/api/read', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiMarkAsRead'])->name('api.read');
            Route::post('/{id}/api/typing', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiTyping'])->name('api.typing');
            Route::put('/{id}/api/status', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiUpdateStatus'])->name('api.status');
            Route::post('/api/presence', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'apiPresencePing'])->name('api.presence');
        });
    }
});
