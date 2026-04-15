<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ApiManagementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BoardMemberController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\DevotionalController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\SystemConfigController;
use App\Http\Controllers\Admin\UpdateController;
use Illuminate\Support\Facades\Route;
use Modules\Avisos\App\Http\Controllers\Admin\AvisosAdminController;
use Modules\Permisao\App\Http\Controllers\AccessHubController;
use Modules\Permisao\App\Http\Controllers\PermissionManagementController;
use Modules\Permisao\App\Http\Controllers\RoleManagementController;
use Modules\Permisao\App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Conteúdo JUBAF: super-admin, presidente, vice-presidente, secretário.
| Núcleo do sistema: apenas super-admin.
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::middleware(['role_or_permission:super-admin|presidente|vice-presidente|secretario|homepage.edit'])->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

        Route::get('/profile', [UserManagementController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');

        Route::resource('carousel', CarouselController::class);
        Route::post('/carousel/toggle', [CarouselController::class, 'toggle'])->name('carousel.toggle');
        Route::post('/carousel/reorder', [CarouselController::class, 'reorder'])->name('carousel.reorder');

        if (module_enabled('Homepage')) {
            Route::get('/homepage', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageAdminController::class, 'index'])->name('homepage.index');
            Route::put('/homepage', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageAdminController::class, 'update'])->name('homepage.update');
            Route::post('/homepage/toggle-section', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageAdminController::class, 'toggleSection'])->name('homepage.toggle-section');
            Route::get('/homepage/contacts', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageContactAdminController::class, 'index'])->name('homepage.contacts.index');
            Route::get('/homepage/contacts/{id}', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageContactAdminController::class, 'show'])->name('homepage.contacts.show')->whereNumber('id');
            Route::delete('/homepage/contacts/{id}', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageContactAdminController::class, 'destroy'])->name('homepage.contacts.destroy')->whereNumber('id');
            Route::get('/homepage/newsletter', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'index'])->name('homepage.newsletter.index');
            Route::get('/homepage/newsletter/compose', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'create'])->name('homepage.newsletter.create');
            Route::post('/homepage/newsletter/send', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'send'])->name('homepage.newsletter.send');
            Route::delete('/homepage/newsletter/{id}', [\Modules\Homepage\App\Http\Controllers\Admin\HomepageNewsletterAdminController::class, 'destroy'])->name('homepage.newsletter.destroy')->whereNumber('id');
            Route::resource('board-members', BoardMemberController::class)->except(['show']);
            Route::post('devotionals/fetch-scripture', [DevotionalController::class, 'fetchScripture'])->name('devotionals.fetch-scripture');
            Route::get('devotionals/bible-books', [DevotionalController::class, 'bibleBooks'])->name('devotionals.bible-books');
            Route::get('devotionals/bible-chapters', [DevotionalController::class, 'bibleChapters'])->name('devotionals.bible-chapters');
            Route::resource('devotionals', DevotionalController::class)->except(['show']);
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

        if (module_enabled('Notificacoes')) {
            Route::post('notificacoes/mark-all-read', [\Modules\Notificacoes\App\Http\Controllers\Admin\NotificacoesAdminController::class, 'markAllAsRead'])->name('notificacoes.markAllAsRead');
            Route::post('notificacoes/{id}/read', [\Modules\Notificacoes\App\Http\Controllers\Admin\NotificacoesAdminController::class, 'markAsRead'])->name('notificacoes.markAsRead');
            Route::resource('notificacoes', \Modules\Notificacoes\App\Http\Controllers\Admin\NotificacoesAdminController::class)->except(['edit', 'update']);
        }

        if (module_enabled('Chat')) {
            Route::prefix('chat')->name('chat.')->group(function () {
                Route::get('/', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'index'])->name('index');
                Route::get('/config', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'config'])->name('config');
                Route::put('/config', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'updateConfig'])->name('config.update');
                Route::get('/{id}', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'show'])->name('show');
                Route::post('/{id}/assign', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'assign'])->name('assign');
                Route::post('/{id}/close', [\Modules\Chat\App\Http\Controllers\Admin\ChatAdminController::class, 'close'])->name('close');

                Route::prefix('api')->name('api.')->group(function () {
                    Route::get('/session/{sessionId}/messages', [\Modules\Chat\App\Http\Controllers\Api\ChatApiController::class, 'getMessages'])->name('session.messages');
                    Route::post('/session/{sessionId}/message', [\Modules\Chat\App\Http\Controllers\Api\ChatApiController::class, 'sendMessage'])->name('session.message');
                    Route::post('/session/{sessionId}/typing', [\Modules\Chat\App\Http\Controllers\Api\ChatApiController::class, 'sendTypingIndicator'])->name('session.typing');
                    Route::post('/session/{sessionId}/read', [\Modules\Chat\App\Http\Controllers\Api\ChatApiController::class, 'markAsRead'])->name('session.read');
                });
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
    });

    Route::middleware(['role:super-admin'])->group(function () {
        if (module_enabled('Igrejas')) {
            require module_path('Igrejas', 'routes/admin.php');
        }

        if (module_enabled('Secretaria')) {
            require module_path('Secretaria', 'routes/admin.php');
        }

        Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
        Route::get('/modules/{moduleName}', [ModuleController::class, 'show'])->name('modules.show');
        Route::post('/modules/{moduleName}/enable', [ModuleController::class, 'enable'])->name('modules.enable');
        Route::post('/modules/{moduleName}/disable', [ModuleController::class, 'disable'])->name('modules.disable');

        Route::get('/seguranca', [AccessHubController::class, 'superadmin'])->name('seguranca.hub');

        Route::resource('users', UserManagementController::class);
        Route::post('/users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::resource('roles', RoleManagementController::class);
        Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionManagementController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{permission}/edit', [PermissionManagementController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}', [PermissionManagementController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [PermissionManagementController::class, 'destroy'])->name('permissions.destroy');

        Route::get('/config', [SystemConfigController::class, 'index'])->name('config.index');
        Route::put('/config', [SystemConfigController::class, 'update'])->name('config.update');
        Route::post('/config/initialize', [SystemConfigController::class, 'initialize'])->name('config.initialize');
        Route::post('/config/branding/upload', [BrandingController::class, 'upload'])->name('config.branding.upload');
        Route::post('/config/branding/restore', [BrandingController::class, 'restore'])->name('config.branding.restore');

        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
        if (module_enabled('Gateway')) {
            require module_path('Gateway', 'routes/admin.php');
        }

        Route::get('/audit/{id}', [AuditLogController::class, 'show'])->name('audit.show');
        Route::post('/audit/clean', [AuditLogController::class, 'clean'])->name('audit.clean');

        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
        Route::post('/backup/{filename}/restore', [BackupController::class, 'restore'])->name('backup.restore');
        Route::get('/backup/{filename}/download', [BackupController::class, 'download'])->name('backup.download');
        Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');

        Route::resource('updates', UpdateController::class)->except(['edit', 'update']);
        Route::post('/updates/upload', [UpdateController::class, 'upload'])->name('updates.upload');
        Route::post('/updates/{id}/apply', [UpdateController::class, 'apply'])->name('updates.apply');
        Route::post('/updates/{id}/rollback', [UpdateController::class, 'rollback'])->name('updates.rollback');
        Route::get('/updates/{id}/backup/download', [UpdateController::class, 'downloadBackup'])->name('updates.download-backup');

        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/', [ApiManagementController::class, 'index'])->name('index');
            Route::get('/create', [ApiManagementController::class, 'create'])->name('create');
            Route::post('/', [ApiManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [ApiManagementController::class, 'show'])->name('show');
            Route::put('/{id}', [ApiManagementController::class, 'update'])->name('update');
            Route::post('/{id}/revoke', [ApiManagementController::class, 'revoke'])->name('revoke');
            Route::post('/{id}/regenerate', [ApiManagementController::class, 'regenerate'])->name('regenerate');
            Route::delete('/{id}', [ApiManagementController::class, 'destroy'])->name('destroy');
        });

        if (module_enabled('Bible')) {
            Route::prefix('biblia-digital')
                ->name('bible.')
                ->group(function () {
                    require module_path('Bible', 'routes/admin-superadmin.php');
                });
        }
    });
});
