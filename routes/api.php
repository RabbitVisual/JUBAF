<?php

use App\Http\Controllers\Api\CepController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas API (Centralizadas)
|--------------------------------------------------------------------------
|
| Todas as rotas de API da aplicação. O prefixo /api e o middleware
| 'api' são aplicados automaticamente pelo bootstrap.
| Organizado por recurso para manutenção e segurança.
|
*/

// =====================================================================
// CEP (público)
// =====================================================================
Route::prefix('cep')->name('cep.')->group(function () {
    Route::get('/buscar', [CepController::class, 'buscar'])->name('buscar');
    Route::get('/validar', [CepController::class, 'validar'])->name('validar');
    Route::get('/cidades/{uf}', [CepController::class, 'cidadesPorUf'])->name('cidades.uf');
    Route::get('/cidades', [CepController::class, 'cidadesPorNome'])->name('cidades.nome');
});

Route::prefix('cep-ranges')->name('cep-ranges.')->group(function () {
    Route::get('/locations', [\Modules\Admin\App\Http\Controllers\CepRangeController::class, 'getLocations'])->name('locations');
});

// =====================================================================
// Notificações API v1 – única API de notificações (web + auth, formato { data })
// Alimenta todo o sistema: painéis, polling, SPA. Sem rotas legadas.
// =====================================================================
$notificationsV1 = \Modules\Notifications\App\Http\Controllers\Api\V1\NotificationController::class;
Route::middleware(['web', 'auth'])->prefix('v1/notifications')->name('notifications.api.')->group(function () use ($notificationsV1) {
    Route::get('/', [$notificationsV1, 'index'])->name('index');
    Route::get('/unread-count', [$notificationsV1, 'unreadCount'])->name('unread-count');
    Route::post('/read-all', [$notificationsV1, 'markAllAsRead'])->name('read-all');
    Route::delete('/clear-all', [$notificationsV1, 'clearAll'])->name('clear-all');
    Route::post('/{userNotification}/read', [$notificationsV1, 'markAsRead'])->name('read');
    Route::delete('/{userNotification}', [$notificationsV1, 'destroy'])->name('destroy');
});

// =====================================================================
// Bible API v1 – API central única (pública, throttle, formato { data })
// =====================================================================
$bibleV1 = \Modules\Bible\App\Http\Controllers\Api\V1\BibleController::class;
Route::middleware(['throttle:60,1'])->prefix('v1/bible')->name('bible.api.')->group(function () use ($bibleV1) {
    Route::get('/versions', [$bibleV1, 'versions'])->name('versions');
    Route::get('/books', [$bibleV1, 'books'])->name('books');
    Route::get('/chapters', [$bibleV1, 'chapters'])->name('chapters');
    Route::get('/verses', [$bibleV1, 'verses'])->name('verses');
    Route::get('/find', [$bibleV1, 'find'])->name('find');
    Route::get('/search', [$bibleV1, 'search'])->name('search');
    Route::get('/random', [$bibleV1, 'random'])->name('random');
    Route::get('/compare', [$bibleV1, 'compare'])->name('compare');
    Route::get('/audio-url', [$bibleV1, 'audioUrl'])->name('audio-url');
    Route::get('/panorama', [$bibleV1, 'panorama'])->name('panorama');
});


// =====================================================================
// PaymentGateway API v1 – gateways ativos e status de pagamento (formato { data })
// =====================================================================
$paymentGatewayV1 = \Modules\PaymentGateway\App\Http\Controllers\Api\V1\PaymentGatewayController::class;
Route::middleware(['throttle:60,1'])->prefix('v1')->group(function () use ($paymentGatewayV1) {
    Route::get('payment-gateways', [$paymentGatewayV1, 'index'])->name('api.payment-gateways.index');
    Route::get('payments/status', [$paymentGatewayV1, 'paymentStatus'])->name('api.payments.status');
    Route::get('payments/{transactionId}/status', [$paymentGatewayV1, 'paymentStatus'])->name('api.payments.status.show');
});

// =====================================================================
// PaymentGateway – Webhook canônico (público para gateways; throttle anti-abuso)
// =====================================================================
Route::middleware(['throttle:120,1'])->prefix('v1/gateway')->name('api.')->group(function () {
    Route::post('/webhook/{driver}', [\Modules\PaymentGateway\App\Http\Controllers\GatewayWebhookController::class, 'handle'])->name('gateway.webhook');
});

if (config('app.debug')) {
    Route::get('/debug/simulate-payment/{driver}', [\Modules\PaymentGateway\App\Http\Controllers\DebugController::class, 'simulatePayment'])->name('debug.simulate-payment');
}

// =====================================================================
// Treasury API v1 – dashboard, entradas, campanhas, metas, relatórios (formato { data })
// =====================================================================
$treasuryV1 = \Modules\Treasury\App\Http\Controllers\Api\V1\TreasuryController::class;
Route::middleware(['throttle:60,1', 'web', 'auth'])->prefix('v1/treasury')->name('treasury.api.')->group(function () use ($treasuryV1) {
    Route::get('dashboard', [$treasuryV1, 'dashboard'])->name('dashboard');
    Route::get('entry-form-options', [$treasuryV1, 'entryFormOptions'])->name('entry-form-options');
    Route::get('entries', [$treasuryV1, 'entries'])->name('entries.index');
    Route::post('entries', [$treasuryV1, 'storeEntry'])->name('entries.store');
    Route::get('entries/{id}', [$treasuryV1, 'entry'])->name('entries.show');
    Route::put('entries/{id}', [$treasuryV1, 'updateEntry'])->name('entries.update');
    Route::delete('entries/{id}', [$treasuryV1, 'destroyEntry'])->name('entries.destroy');
    Route::post('entries/import-payment/{paymentId}', [$treasuryV1, 'importPayment'])->name('entries.import-payment');
    Route::get('campaigns', [$treasuryV1, 'campaigns'])->name('campaigns.index');
    Route::post('campaigns', [$treasuryV1, 'storeCampaign'])->name('campaigns.store');
    Route::get('campaigns/{id}', [$treasuryV1, 'campaign'])->name('campaigns.show');
    Route::put('campaigns/{id}', [$treasuryV1, 'updateCampaign'])->name('campaigns.update');
    Route::delete('campaigns/{id}', [$treasuryV1, 'destroyCampaign'])->name('campaigns.destroy');
    Route::get('goals', [$treasuryV1, 'goals'])->name('goals.index');
    Route::post('goals', [$treasuryV1, 'storeGoal'])->name('goals.store');
    Route::get('goals/{id}', [$treasuryV1, 'goal'])->name('goals.show');
    Route::put('goals/{id}', [$treasuryV1, 'updateGoal'])->name('goals.update');
    Route::delete('goals/{id}', [$treasuryV1, 'destroyGoal'])->name('goals.destroy');
    Route::get('reports', [$treasuryV1, 'reports'])->name('reports.index');
    Route::get('permissions', [$treasuryV1, 'permissions'])->name('permissions.index');
    Route::get('closings', [$treasuryV1, 'closings'])->name('closings.index');
    Route::post('closings/{id}/approve-for-assembly', [$treasuryV1, 'approveClosingForAssembly'])->name('closings.approve-for-assembly');
});







// =====================================================================
// API v1 – Autenticação Sanctum
// =====================================================================
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('admins', \Modules\Admin\App\Http\Controllers\AdminController::class)->names('admin');
    Route::apiResource('events', \Modules\Events\App\Http\Controllers\EventsController::class)->names('events');
    Route::apiResource('memberpanels', \Modules\MemberPanel\App\Http\Controllers\MemberPanelController::class)->names('memberpanel');
    Route::apiResource('homepages', \Modules\HomePage\App\Http\Controllers\HomePageController::class)->names('api.homepage');
});

// Bible v1 (auth:sanctum) – apiResource bibles; compare está em api/v1/bible/compare
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bibles', \Modules\Bible\App\Http\Controllers\BibleController::class)->names('bible');
});


// =====================================================================
// Bible: pesquisa e recursos via rotas Bible / api/v1/bibles conforme módulo.
// =====================================================================
