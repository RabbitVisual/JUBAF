<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas do Painel Admin (Centralizadas)
|--------------------------------------------------------------------------
|
| Middleware: auth, verified, admin. Grupos `permission:` usam OR por slug.
|
*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {

    // =====================================================================
    // PaymentGateway — finanças / pagamentos
    // =====================================================================
    Route::middleware(['permission:gerenciar_financeiro'])->name('admin.')->group(function () {
        Route::get('/payment-gateways/statistics', [\Modules\PaymentGateway\App\Http\Controllers\Admin\PaymentGatewayController::class, 'statistics'])->name('payment-gateways.statistics');
        Route::resource('payment-gateways', \Modules\PaymentGateway\App\Http\Controllers\Admin\PaymentGatewayController::class)->except(['create', 'store', 'destroy', 'show']);
        Route::get('/transactions', [\Modules\PaymentGateway\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{payment}', [\Modules\PaymentGateway\App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{payment}/comprovante', [\Modules\PaymentGateway\App\Http\Controllers\Admin\TransactionController::class, 'receipt'])->name('transactions.receipt');
        Route::post('/transactions/{payment}/cancel', [\Modules\PaymentGateway\App\Http\Controllers\Admin\TransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::delete('/transactions/{payment}', [\Modules\PaymentGateway\App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('transactions.destroy');
    });

    // =====================================================================
    // Admin (Core)
    // =====================================================================
    Route::name('admin.')->group(function () {
        Route::get('/', [\Modules\Admin\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [\Modules\Admin\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

        Route::middleware([\Modules\Admin\App\Http\Middleware\EnsureUserIsTechnicalAdmin::class])->group(function () {
            Route::get('/modules', [\Modules\Admin\App\Http\Controllers\ModuleController::class, 'index'])->name('modules.index');
            Route::post('/modules/{module}/enable', [\Modules\Admin\App\Http\Controllers\ModuleController::class, 'enable'])->name('modules.enable');
            Route::post('/modules/{module}/disable', [\Modules\Admin\App\Http\Controllers\ModuleController::class, 'disable'])->name('modules.disable');
            Route::resource('cep-ranges', \Modules\Admin\App\Http\Controllers\CepRangeController::class);
        });

        Route::middleware(['permission:gerenciar_usuarios,ver_membros'])->group(function () {
            Route::get('users/import', [\Modules\Admin\App\Http\Controllers\MemberImportController::class, 'showImportForm'])->name('users.import');
            Route::post('users/import', [\Modules\Admin\App\Http\Controllers\MemberImportController::class, 'import'])->name('users.import.post');
            Route::get('users/import/template', [\Modules\Admin\App\Http\Controllers\MemberImportController::class, 'downloadTemplate'])->name('users.import.template');
            Route::resource('users', \Modules\Admin\App\Http\Controllers\UserController::class);
        });

        Route::get('/profile', [\Modules\Admin\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [\Modules\Admin\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\Modules\Admin\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/2fa', [\Modules\Admin\App\Http\Controllers\TwoFactorController::class, 'show'])->name('profile.2fa.show');
        Route::post('/profile/2fa/setup', [\Modules\Admin\App\Http\Controllers\TwoFactorController::class, 'setup'])->name('profile.2fa.setup');
        Route::post('/profile/2fa/confirm', [\Modules\Admin\App\Http\Controllers\TwoFactorController::class, 'confirm'])->name('profile.2fa.confirm');
        Route::post('/profile/2fa/disable', [\Modules\Admin\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('profile.2fa.disable');
        Route::get('/profile/2fa/qr', [\Modules\Admin\App\Http\Controllers\TwoFactorController::class, 'qrImage'])->name('profile.2fa.qr');

        Route::middleware([\Modules\Admin\App\Http\Middleware\EnsureUserIsTechnicalAdmin::class])->group(function () {
            Route::get('/password-resets', [\Modules\Admin\App\Http\Controllers\PasswordResetController::class, 'index'])->name('password-resets.index');
            Route::get('/password-resets/settings', [\Modules\Admin\App\Http\Controllers\PasswordResetController::class, 'settings'])->name('password-resets.settings');
            Route::put('/password-resets/settings', [\Modules\Admin\App\Http\Controllers\PasswordResetController::class, 'updateSettings'])->name('password-resets.settings.update');
            Route::get('/settings', [\Modules\Admin\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [\Modules\Admin\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
            Route::post('/settings/test-email', [\Modules\Admin\App\Http\Controllers\SettingsController::class, 'testEmail'])->name('settings.test-email');
            Route::post('/settings/activate-maintenance', [\Modules\Admin\App\Http\Controllers\SettingsController::class, 'activateMaintenance'])->name('settings.activate-maintenance');
            Route::post('/settings/deactivate-maintenance', [\Modules\Admin\App\Http\Controllers\SettingsController::class, 'deactivateMaintenance'])->name('settings.deactivate-maintenance');
        });

        Route::delete('/notifications/clear-my-inbox', [\Modules\Admin\App\Http\Controllers\NotificationController::class, 'clearMyInbox'])->name('notifications.clear-my-inbox');

        Route::middleware(['permission:notificacoes_broadcast'])->group(function () {
            Route::get('/notifications/control/dashboard', [\Modules\Notifications\App\Http\Controllers\Admin\NotificationDashboardController::class, 'index'])->name('notifications.control.dashboard');
            Route::get('/notifications/control/dlq', [\Modules\Notifications\App\Http\Controllers\Admin\NotificationDlqController::class, 'index'])->name('notifications.dlq.index');
            Route::post('/notifications/control/dlq/{failed}/retry', [\Modules\Notifications\App\Http\Controllers\Admin\NotificationDlqController::class, 'retry'])->name('notifications.dlq.retry');
            Route::get('/notifications/control/broadcast', [\Modules\Notifications\App\Http\Controllers\Admin\NotificationBroadcastController::class, 'create'])->name('notifications.broadcast.create');
            Route::post('/notifications/control/broadcast', [\Modules\Notifications\App\Http\Controllers\Admin\NotificationBroadcastController::class, 'store'])->name('notifications.broadcast.store');
            Route::resource('notifications/templates', \Modules\Notifications\App\Http\Controllers\Admin\NotificationTemplatesController::class)->parameters(['templates' => 'template'])->names('notifications.templates');
        });

        Route::middleware(['permission:gerenciar_usuarios,notificacoes_broadcast'])->group(function () {
            Route::resource('notifications', \Modules\Admin\App\Http\Controllers\NotificationController::class);
        });

        Route::middleware(['permission:gerenciar_diretoria'])->prefix('homepage')->name('homepage.')->group(function () {
            Route::get('/', function () {
                return redirect()->route('admin.homepage.settings.index');
            })->name('index');
            Route::get('/settings', [\Modules\Admin\App\Http\Controllers\HomePageSettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [\Modules\Admin\App\Http\Controllers\HomePageSettingsController::class, 'update'])->name('settings.update');
            Route::resource('carousel', \Modules\Admin\App\Http\Controllers\CarouselController::class)->except(['show'])->parameters(['carousel' => 'slide']);
            Route::post('/carousel/order', [\Modules\Admin\App\Http\Controllers\CarouselController::class, 'updateOrder'])->name('carousel.order');
            Route::post('/carousel/{slide}/toggle', [\Modules\Admin\App\Http\Controllers\CarouselController::class, 'toggleActive'])->name('carousel.toggle');
            Route::post('/carousel/{slide}/duplicate', [\Modules\Admin\App\Http\Controllers\CarouselController::class, 'duplicate'])->name('carousel.duplicate');
            Route::post('contacts/mark-read', [\Modules\Admin\App\Http\Controllers\ContactController::class, 'markRead'])->name('contacts.mark-read');
            Route::resource('contacts', \Modules\Admin\App\Http\Controllers\ContactController::class);
            Route::get('newsletter/export', [\Modules\Admin\App\Http\Controllers\NewsletterController::class, 'export'])->name('newsletter.export');
            Route::resource('newsletter', \Modules\Admin\App\Http\Controllers\NewsletterController::class);
            Route::post('/newsletter/send', [\Modules\Admin\App\Http\Controllers\NewsletterController::class, 'send'])->name('newsletter.send');
        });

        Route::middleware(['permission:acesso_biblia'])->group(function () {
            Route::prefix('bible')->name('bible.')->group(function () {
                Route::get('import', [\Modules\Bible\App\Http\Controllers\BibleController::class, 'import'])->name('import');
                Route::post('import', [\Modules\Bible\App\Http\Controllers\BibleController::class, 'storeImport'])->name('import.store');
                Route::resource('plans', \Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class);
                Route::get('plans/{id}/generate', [\Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class, 'generator'])->name('plans.generate');
                Route::post('plans/{id}/generate', [\Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class, 'processGeneration'])->name('plans.process-generation');
                Route::get('plans/{planId}/days/{dayId}/edit', [\Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class, 'editDay'])->name('plans.days.edit');
                Route::post('plans/days/{dayId}/content', [\Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class, 'storeContent'])->name('plans.content.store');
                Route::put('plans/content/{contentId}', [\Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class, 'updateContent'])->name('plans.content.update');
                Route::delete('plans/content/{contentId}', [\Modules\Bible\App\Http\Controllers\Admin\BiblePlanController::class, 'destroyContent'])->name('plans.content.destroy');
                Route::get('reports/church-plan', [\Modules\Bible\App\Http\Controllers\Admin\BibleReportController::class, 'churchPlan'])->name('reports.church-plan');
            });

            Route::resource('bible', \Modules\Bible\App\Http\Controllers\Admin\BibleController::class);
            Route::get('/bible/import', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'importForm'])->name('bible.import');
            Route::post('/bible/import', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'store'])->name('bible.import.store');
            Route::get('/bible/{version}/book/{book}', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'viewBook'])->name('bible.book');
            Route::get('/bible/{version}/book/{book}/chapter/{chapter}', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'viewChapter'])->name('bible.chapter');
            Route::get('/bible/{bible}/chapter-audio', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'chapterAudioIndex'])->name('bible.chapter-audio.index');
            Route::get('/bible/{bible}/chapter-audio/template', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'chapterAudioTemplate'])->name('bible.chapter-audio.template');
            Route::post('/bible/{bible}/chapter-audio', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'chapterAudioStore'])->name('bible.chapter-audio.store');
            Route::delete('/bible/{bible}/chapter-audio/{chapter_audio}', [\Modules\Bible\App\Http\Controllers\Admin\BibleController::class, 'chapterAudioDestroy'])->name('bible.chapter-audio.destroy');
        });

        Route::middleware(['permission:gerenciar_igrejas'])->group(function () {
            Route::resource('churches', \Modules\Church\App\Http\Controllers\Admin\ChurchController::class);
        });

        Route::middleware(['permission:gerenciar_eventos'])->prefix('events')->name('events.')->group(function () {
            Route::resource('events', \Modules\Events\App\Http\Controllers\Admin\EventController::class);
            Route::get('events/{event}/duplicate', [\Modules\Events\App\Http\Controllers\Admin\EventController::class, 'duplicate'])->name('events.duplicate');
            Route::post('events/{event}/batches', [\Modules\Events\App\Http\Controllers\Admin\EventController::class, 'storeBatch'])->name('events.batches.store');
            Route::put('events/{event}/batches/{batch}', [\Modules\Events\App\Http\Controllers\Admin\EventController::class, 'updateBatch'])->name('events.batches.update');
            Route::delete('events/{event}/batches/{batch}', [\Modules\Events\App\Http\Controllers\Admin\EventController::class, 'destroyBatch'])->name('events.batches.destroy');
            Route::get('events/{event}/registrations', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'index'])->name('registrations.index');
            Route::get('events/{event}/registrations/export-pdf', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'exportPdf'])->name('registrations.export-pdf');
            Route::get('events/{event}/registrations/export-badges', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'exportBadges'])->name('registrations.export-badges');
            Route::get('events/{event}/registrations/export-excel', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'exportExcel'])->name('registrations.export-excel');
            Route::get('events/{event}/registrations/{registration}', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'show'])->name('registrations.show');
            Route::post('events/{event}/registrations/{registration}/confirm', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'confirm'])->name('registrations.confirm');
            Route::post('events/{event}/registrations/{registration}/cancel', [\Modules\Events\App\Http\Controllers\Admin\RegistrationController::class, 'cancel'])->name('registrations.cancel');
            Route::get('checkin', [\Modules\Events\App\Http\Controllers\Admin\CheckinController::class, 'index'])->name('checkin.index');
            Route::get('checkin/scanner', [\Modules\Events\App\Http\Controllers\Admin\CheckinController::class, 'scannerFullscreen'])->name('checkin.scanner');
            Route::post('checkin/validate', [\Modules\Events\App\Http\Controllers\Admin\CheckinController::class, 'validateCheckin'])->name('checkin.validate');
        });

        Route::middleware(['permission:governance_manage,governance_view'])->prefix('governance')->name('governance.')->group(function () {
            Route::resource('assemblies', \Modules\Governance\Http\Controllers\Admin\AssemblyController::class);
            Route::get('assemblies/{assembly}/minute/edit', [\Modules\Governance\Http\Controllers\Admin\MinuteController::class, 'edit'])->name('assemblies.minute.edit');
            Route::put('assemblies/{assembly}/minute', [\Modules\Governance\Http\Controllers\Admin\MinuteController::class, 'update'])->name('assemblies.minute.update');
            Route::post('assemblies/{assembly}/minute/publish', [\Modules\Governance\Http\Controllers\Admin\MinuteController::class, 'publish'])->name('assemblies.minute.publish');
            Route::resource('communications', \Modules\Governance\Http\Controllers\Admin\OfficialCommunicationController::class)->except(['show']);
        });

        Route::middleware(['permission:governance_manage,governance_view'])->prefix('diretoria')->name('diretoria.')->group(function () {
            Route::get('atas', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'index'])->name('minutes.index');
            Route::get('atas/{board_minute}/download', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'download'])->name('minutes.download');
        });
        Route::middleware(['permission:governance_manage'])->prefix('diretoria')->name('diretoria.')->group(function () {
            Route::get('atas/criar', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'create'])->name('minutes.create');
            Route::post('atas', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'store'])->name('minutes.store');
            Route::get('atas/{board_minute}/editar', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'edit'])->name('minutes.edit');
            Route::put('atas/{board_minute}', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'update'])->name('minutes.update');
            Route::delete('atas/{board_minute}', [\Modules\Diretoria\Http\Controllers\Admin\BoardMinuteController::class, 'destroy'])->name('minutes.destroy');
        });

        Route::middleware(['permission:council_manage,council_view'])->prefix('council')->name('council.')->group(function () {
            Route::resource('members', \Modules\CoordinationCouncil\Http\Controllers\Admin\CouncilMemberController::class);
            Route::resource('meetings', \Modules\CoordinationCouncil\Http\Controllers\Admin\CouncilMeetingController::class);
            Route::post('meetings/{meeting}/attendance', [\Modules\CoordinationCouncil\Http\Controllers\Admin\CouncilMeetingController::class, 'saveAttendance'])->name('meetings.attendance');
        });

        Route::middleware(['permission:field_manage,field_view'])->prefix('field')->name('field.')->group(function () {
            Route::resource('visits', \Modules\FieldOutreach\Http\Controllers\Admin\FieldVisitController::class);
        });
    });
});
