<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas do Painel do Membro (Centralizadas)
|--------------------------------------------------------------------------
|
| Todas as rotas destinadas a usuários autenticados (membros) no painel
| /painel. Middleware: auth, verified. Organizado por módulo para
| manutenção e segurança global.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // =====================================================================
    // MemberPanel (Core): Dashboard, Perfil, Ministérios, Notificações, Bíblia, Tesouraria
    // =====================================================================
    Route::prefix('painel')->name('memberpanel.')->group(function () {
        // Dashboard
        Route::get('/', [\Modules\MemberPanel\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [\Modules\MemberPanel\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

        // Perfil
        Route::get('/perfil', [\Modules\MemberPanel\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/perfil/editar', [\Modules\MemberPanel\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/perfil', [\Modules\MemberPanel\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/perfil/fotos/{photo}/set-active', [\Modules\MemberPanel\App\Http\Controllers\ProfileController::class, 'setActivePhoto'])->name('profile.photo.active');
        Route::delete('/perfil/fotos/{photo}', [\Modules\MemberPanel\App\Http\Controllers\ProfileController::class, 'deletePhoto'])->name('profile.photo.destroy');

        // Notificações
        Route::get('/notifications', [\Modules\MemberPanel\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::delete('/notificacoes/clear-all', [\Modules\MemberPanel\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
        Route::delete('/notificacoes/{notification}', [\Modules\MemberPanel\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Preferências de notificações (Notifications Engine v2)
        Route::get('/preferencias/notificacoes', [\Modules\Notifications\App\Http\Controllers\MemberPanel\NotificationPreferencesController::class, 'index'])->name('preferences.notifications.index');
        Route::put('/preferencias/notificacoes', [\Modules\Notifications\App\Http\Controllers\MemberPanel\NotificationPreferencesController::class, 'update'])->name('preferences.notifications.update');

        // Bíblia (rotas específicas primeiro para evitar conflito com {version?})
        Route::get('/biblia/interlinear', [\Modules\Bible\App\Http\Controllers\InterlinearController::class, 'index'])->name('bible.interlinear');
        Route::get('/biblia/interlinear/data', [\Modules\Bible\App\Http\Controllers\InterlinearController::class, 'getData'])->name('bible.interlinear.data');
        Route::get('/biblia/interlinear/books', [\Modules\Bible\App\Http\Controllers\InterlinearController::class, 'getBooksMetadata'])->name('bible.interlinear.books');
        Route::get('/biblia/strong/{number}', [\Modules\Bible\App\Http\Controllers\InterlinearController::class, 'getStrongDefinition'])->name('bible.strong.show');
        Route::get('/biblia', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'index'])->name('bible.index');
        Route::get('/biblia/buscar', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'search'])->name('bible.search');
        Route::get('/biblia/favoritos', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'favorites'])->name('bible.favorites');
        Route::post('/biblia/versiculo/{verse}/favorito', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'addFavorite'])->name('bible.favorite.add');
        Route::delete('/biblia/versiculo/{verse}/favorito', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'removeFavorite'])->name('bible.favorite.remove');
        Route::get('/biblia/{version?}', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'read'])->name('bible.read');
        Route::get('/biblia/{version}/livro/{book}', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'showBook'])->name('bible.book');
        Route::get('/biblia/{version}/livro/{book}/capitulo/{chapter}', [\Modules\Bible\App\Http\Controllers\MemberPanel\BibleController::class, 'showChapter'])->name('bible.chapter');

        Route::middleware(['memberpanel.delegate'])->prefix('acessos-painel')->name('panel-access.')->group(function () {
            Route::get('/', [\Modules\MemberPanel\App\Http\Controllers\PanelAccessController::class, 'index'])->name('index');
            Route::post('/', [\Modules\MemberPanel\App\Http\Controllers\PanelAccessController::class, 'store'])->name('store');
            Route::delete('/{grant}', [\Modules\MemberPanel\App\Http\Controllers\PanelAccessController::class, 'destroy'])->name('destroy');
        });

        // Tesouraria (proxy no painel)
        Route::middleware(['memberpanel.module:treasury'])->prefix('tesouraria')->name('treasury.')->group(function () {
            $treasuryController = \Modules\Treasury\App\Http\Controllers\MemberPanel\TreasuryController::class;
            Route::get('/', [$treasuryController, 'dashboard'])->name('dashboard');
            Route::get('/dashboard', [$treasuryController, 'dashboard'])->name('dashboard.index');
            Route::get('/transparencia', [$treasuryController, 'transparency'])->name('transparency');
            Route::get('/entradas', [$treasuryController, 'entriesIndex'])->name('entries.index');
            Route::get('/entradas/criar', [$treasuryController, 'entriesCreate'])->name('entries.create');
            Route::post('/entradas', [$treasuryController, 'entriesStore'])->name('entries.store');
            Route::get('/entradas/{entry}/editar', [$treasuryController, 'entriesEdit'])->name('entries.edit');
            Route::put('/entradas/{entry}', [$treasuryController, 'entriesUpdate'])->name('entries.update');
            Route::delete('/entradas/{entry}', [$treasuryController, 'entriesDestroy'])->name('entries.destroy');
            Route::post('/entradas/importar', [$treasuryController, 'proxy'])->defaults('controller', 'entries')->defaults('method', 'importPayment')->name('import');
            Route::get('/campanhas', [$treasuryController, 'campaignsIndex'])->name('campaigns.index');
            Route::get('/campanhas/criar', [$treasuryController, 'campaignsCreate'])->name('campaigns.create');
            Route::post('/campanhas', [$treasuryController, 'campaignsStore'])->name('campaigns.store');
            Route::get('/campanhas/{campaign}', [$treasuryController, 'campaignsShow'])->name('campaigns.show');
            Route::get('/campanhas/{campaign}/editar', [$treasuryController, 'campaignsEdit'])->name('campaigns.edit');
            Route::put('/campanhas/{campaign}', [$treasuryController, 'campaignsUpdate'])->name('campaigns.update');
            Route::delete('/campanhas/{campaign}', [$treasuryController, 'campaignsDestroy'])->name('campaigns.destroy');
            Route::get('/metas', [$treasuryController, 'goalsIndex'])->name('goals.index');
            Route::get('/metas/criar', [$treasuryController, 'goalsCreate'])->name('goals.create');
            Route::post('/metas', [$treasuryController, 'goalsStore'])->name('goals.store');
            Route::get('/metas/{goal}', [$treasuryController, 'goalsShow'])->name('goals.show');
            Route::get('/metas/{goal}/editar', [$treasuryController, 'goalsEdit'])->name('goals.edit');
            Route::put('/metas/{goal}', [$treasuryController, 'goalsUpdate'])->name('goals.update');
            Route::delete('/metas/{goal}', [$treasuryController, 'goalsDestroy'])->name('goals.destroy');
            Route::get('/relatorios', [$treasuryController, 'reportsIndex'])->name('reports.index');
            Route::get('/relatorios/exportar/excel', [$treasuryController, 'reportsExportExcel'])->name('reports.export.excel');
            Route::get('/relatorios/exportar/pdf', [$treasuryController, 'reportsExportPdf'])->name('reports.export.pdf');
            Route::get('/relatorios/exportar', [$treasuryController, 'reportsExport'])->name('reports.export');
            Route::middleware(['permission:delegar_tesouraria'])->group(function () use ($treasuryController) {
                Route::get('/permissoes', [$treasuryController, 'permissionsIndex'])->name('permissions.index');
                Route::get('/permissoes/criar', [$treasuryController, 'permissionsCreate'])->name('permissions.create');
                Route::post('/permissoes', [$treasuryController, 'permissionsStore'])->name('permissions.store');
                Route::get('/permissoes/{treasuryPermission}/editar', [$treasuryController, 'permissionsEdit'])->name('permissions.edit');
                Route::put('/permissoes/{treasuryPermission}', [$treasuryController, 'permissionsUpdate'])->name('permissions.update');
                Route::delete('/permissoes/{treasuryPermission}', [$treasuryController, 'permissionsDestroy'])->name('permissions.destroy');
            });
        });

        // PaymentGateway - Doações
        Route::get('/minhas-doacoes', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'index'])->name('donations.index');
        Route::get('/doacoes', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'create'])->name('donations.create');
        Route::post('/doacoes', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'store'])->name('donations.store');
        Route::get('/doacoes/{transactionId}', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'show'])->name('donations.show');
        Route::get('/doacoes/{transactionId}/retry', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'retry'])->name('donations.retry');
        Route::post('/doacoes/{transactionId}/retry', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'updateGateway'])->name('donations.update-gateway');
        Route::get('/doacoes/{transactionId}/status', [\Modules\PaymentGateway\App\Http\Controllers\MemberPanel\DonationController::class, 'checkStatus'])->name('donations.check-status');

        // Events (JUBAF) — catálogo e inscrições no painel (memberpanel.events.*)
        Route::prefix('eventos')->name('events.')->group(function () {
            $eventsMember = \Modules\Events\App\Http\Controllers\MemberPanel\EventController::class;
            Route::get('/', [$eventsMember, 'index'])->name('index');
            Route::get('/minhas-inscricoes', [$eventsMember, 'myRegistrations'])->name('my-registrations');
            Route::get('/inscricao/{registration}', [$eventsMember, 'showRegistration'])->name('show-registration');
            Route::get('/inscricao/{registration}/confirmada', [$eventsMember, 'registrationConfirmed'])->name('registration.confirmed');
            Route::get('/inscricao/{registration}/retentar', [$eventsMember, 'retryRegistration'])->name('registration.retry');
            Route::post('/inscricao/{registration}/gateway', [$eventsMember, 'updateRegistrationGateway'])->name('registration.update-gateway');
            Route::post('/{event:slug}/inscrever', [$eventsMember, 'register'])->name('register');
            Route::get('/{event:slug}', [$eventsMember, 'show'])->name('show');
        });

        // Institucional — controladores nos próprios módulos (gestão no /painel com governance_manage, etc.)
        Route::middleware(['memberpanel.module:governance'])->prefix('institucional/governanca')->name('governance.')->group(function () {
            $governance = \Modules\Governance\Http\Controllers\MemberPanel\GovernanceMemberPanelController::class;
            Route::get('/', [$governance, 'dashboard'])->name('dashboard');
            Route::get('/assembleias', [$governance, 'assembliesIndex'])->name('assemblies.index');
            Route::get('/assembleias/criar', [$governance, 'assembliesCreate'])->name('assemblies.create');
            Route::post('/assembleias', [$governance, 'assembliesStore'])->name('assemblies.store');
            Route::get('/assembleias/{assembly}', [$governance, 'assembliesShow'])->name('assemblies.show');
            Route::get('/assembleias/{assembly}/editar', [$governance, 'assembliesEdit'])->name('assemblies.edit');
            Route::put('/assembleias/{assembly}', [$governance, 'assembliesUpdate'])->name('assemblies.update');
            Route::delete('/assembleias/{assembly}', [$governance, 'assembliesDestroy'])->name('assemblies.destroy');
            Route::get('/assembleias/{assembly}/ata/editar', [$governance, 'assembliesMinuteEdit'])->name('assemblies.minute.edit');
            Route::put('/assembleias/{assembly}/ata', [$governance, 'assembliesMinuteUpdate'])->name('assemblies.minute.update');
            Route::post('/assembleias/{assembly}/ata/publicar', [$governance, 'assembliesMinutePublish'])->name('assemblies.minute.publish');
            Route::get('/comunicados', [$governance, 'communicationsIndex'])->name('communications.index');
            Route::get('/comunicados/criar', [$governance, 'communicationsCreate'])->name('communications.create');
            Route::post('/comunicados', [$governance, 'communicationsStore'])->name('communications.store');
            Route::get('/comunicados/{communication}', [$governance, 'communicationsShow'])->name('communications.show');
            Route::get('/comunicados/{communication}/editar', [$governance, 'communicationsEdit'])->name('communications.edit');
            Route::put('/comunicados/{communication}', [$governance, 'communicationsUpdate'])->name('communications.update');
            Route::delete('/comunicados/{communication}', [$governance, 'communicationsDestroy'])->name('communications.destroy');
        });

        Route::middleware(['memberpanel.module:council'])->prefix('institucional/conselho')->name('council.')->group(function () {
            $council = \Modules\CoordinationCouncil\Http\Controllers\MemberPanel\CouncilMemberPanelController::class;
            Route::get('/', [$council, 'dashboard'])->name('dashboard');
            Route::get('/membros', [$council, 'membersIndex'])->name('members.index');
            Route::get('/membros/criar', [$council, 'membersCreate'])->name('members.create');
            Route::post('/membros', [$council, 'membersStore'])->name('members.store');
            Route::get('/membros/{member}', [$council, 'membersShow'])->name('members.show');
            Route::get('/membros/{member}/editar', [$council, 'membersEdit'])->name('members.edit');
            Route::put('/membros/{member}', [$council, 'membersUpdate'])->name('members.update');
            Route::delete('/membros/{member}', [$council, 'membersDestroy'])->name('members.destroy');
            Route::get('/reunioes', [$council, 'meetingsIndex'])->name('meetings.index');
            Route::get('/reunioes/criar', [$council, 'meetingsCreate'])->name('meetings.create');
            Route::post('/reunioes', [$council, 'meetingsStore'])->name('meetings.store');
            Route::get('/reunioes/{meeting}', [$council, 'meetingsShow'])->name('meetings.show');
            Route::get('/reunioes/{meeting}/editar', [$council, 'meetingsEdit'])->name('meetings.edit');
            Route::put('/reunioes/{meeting}', [$council, 'meetingsUpdate'])->name('meetings.update');
            Route::delete('/reunioes/{meeting}', [$council, 'meetingsDestroy'])->name('meetings.destroy');
            Route::post('/reunioes/{meeting}/presencas', [$council, 'meetingsSaveAttendance'])->name('meetings.attendance');
        });

        Route::middleware(['memberpanel.module:field'])->prefix('institucional/campo')->name('field.')->group(function () {
            $field = \Modules\FieldOutreach\Http\Controllers\MemberPanel\FieldMemberPanelController::class;
            Route::get('/', [$field, 'dashboard'])->name('dashboard');
            Route::get('/visitas', [$field, 'visitsIndex'])->name('visits.index');
            Route::get('/visitas/criar', [$field, 'visitsCreate'])->name('visits.create');
            Route::post('/visitas', [$field, 'visitsStore'])->name('visits.store');
            Route::get('/visitas/{visit}', [$field, 'visitsShow'])->name('visits.show');
            Route::get('/visitas/{visit}/editar', [$field, 'visitsEdit'])->name('visits.edit');
            Route::put('/visitas/{visit}', [$field, 'visitsUpdate'])->name('visits.update');
            Route::delete('/visitas/{visit}', [$field, 'visitsDestroy'])->name('visits.destroy');
        });
    });

    // =====================================================================
    // Church Module - Gestão de Vínculos associacionais (memberpanel.churches.*)
    // =====================================================================
    Route::middleware(['memberpanel.module:churches'])->prefix('painel')->name('memberpanel.')->group(function () {
        Route::resource('igrejas', \Modules\Church\App\Http\Controllers\MemberPanel\ChurchController::class)
            ->parameters(['igrejas' => 'church'])
            ->names('churches');
    });

});
