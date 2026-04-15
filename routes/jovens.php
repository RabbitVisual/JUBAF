<?php

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\InterlinearController;
use Modules\Bible\App\Http\Controllers\PainelJovens\FavoriteController as BibleFavoriteController;
use Modules\Bible\App\Http\Controllers\PainelJovens\JovensPanelBibleController;
use Modules\Bible\App\Http\Controllers\PainelJovens\JovensPanelPlanReaderController;
use Modules\Bible\App\Http\Controllers\PainelJovens\JovensPanelReadingPlanController;
use Modules\Igrejas\App\Http\Controllers\PainelJovens\MinhaIgrejaController;
use Modules\Avisos\App\Http\Controllers\AvisosPainelController;
use Modules\Blog\App\Http\Controllers\BlogPainelController;
use Modules\PainelJovens\App\Http\Controllers\ChatController;
use Modules\PainelJovens\App\Http\Controllers\DashboardController;
use Modules\PainelJovens\App\Http\Controllers\NotificacoesController;
use App\Http\Controllers\Profile\DataChangeRequestController;
use Modules\PainelJovens\App\Http\Controllers\ProfileController;
use Modules\PainelJovens\App\Http\Controllers\DevotionalController;

/*
|--------------------------------------------------------------------------
| Painel de Jovens JUBAF (Unijovem) — rotas canónicas jovens.*
|--------------------------------------------------------------------------
*/

Route::permanentRedirect('/consulta', '/jovens');
Route::permanentRedirect('/consulta/dashboard', '/jovens/dashboard');

Route::prefix('jovens')->name('jovens.')->middleware(['auth', 'role:jovens', 'jovens.panel'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/solicitar-dados-sensiveis', [DataChangeRequestController::class, 'store'])->name('profile.sensitive-data-request.store');

    if (class_exists(\App\Models\Devotional::class)) {
        Route::get('/devocionais', [DevotionalController::class, 'index'])->name('devotionals.index');
        Route::get('/devocionais/{devotional:slug}', [DevotionalController::class, 'show'])->name('devotionals.show');
    }

    if (module_enabled('Igrejas')) {
        Route::get('/igreja', [MinhaIgrejaController::class, 'index'])->name('igreja.index');
    }

    if (module_enabled('Notificacoes')) {
        Route::get('/notificacoes', [NotificacoesController::class, 'index'])->name('notificacoes.index');
        Route::get('/notificacoes/{id}', [NotificacoesController::class, 'show'])->name('notificacoes.show');
    }

    if (module_enabled('Secretaria')) {
        Route::prefix('secretaria')->name('secretaria.')->middleware(['permission:secretaria.minutes.view'])->group(function () {
            require module_path('Secretaria', 'routes/painel-operacional.php');
        });
    }

    if (module_enabled('Chat')) {
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::get('/page', function () {
                return view('chat::paineljovens.chat.index');
            })->name('page');
            Route::post('/', [ChatController::class, 'store'])->name('store');
            Route::get('/users', [ChatController::class, 'getAvailableUsers'])->name('users');
            Route::get('/session/{sessionId}/messages', [ChatController::class, 'getMessages'])->name('messages');
            Route::post('/session/{sessionId}/message', [ChatController::class, 'sendMessage'])->name('send');
        });
    }

    if (module_enabled('Bible')) {
        Route::prefix('biblia')->name('bible.')->group(function () {
            Route::prefix('planos')->group(function () {
                Route::get('/', [JovensPanelReadingPlanController::class, 'index'])->name('plans.index');
                Route::get('/catalog', [JovensPanelReadingPlanController::class, 'catalog'])->name('plans.catalog');
                Route::get('/{id}/preview', [JovensPanelReadingPlanController::class, 'preview'])->whereNumber('id')->name('plans.preview');
                Route::post('/{id}/subscribe', [JovensPanelReadingPlanController::class, 'subscribe'])->whereNumber('id')->name('plans.subscribe');
                Route::get('/resume/{id}', [JovensPanelReadingPlanController::class, 'show'])->whereNumber('id')->name('plans.show');
                Route::post('/{subscriptionId}/recalculate', [JovensPanelReadingPlanController::class, 'recalculate'])->whereNumber('subscriptionId')->name('plans.recalculate');
                Route::get('/download/{id}/pdf', [JovensPanelReadingPlanController::class, 'downloadPdf'])->whereNumber('id')->name('plans.pdf');

                Route::get('/read/{subscriptionId}/{day}', [JovensPanelPlanReaderController::class, 'read'])->name('reader');
                Route::post('/read/{subscriptionId}/{dayId}/complete', [JovensPanelPlanReaderController::class, 'complete'])->name('reader.complete');
                Route::post('/read/{subscriptionId}/{dayId}/uncomplete', [JovensPanelPlanReaderController::class, 'uncomplete'])->name('reader.uncomplete');
                Route::get('/read/{subscriptionId}/{dayId}/congratulations', [JovensPanelPlanReaderController::class, 'congratulations'])->name('reader.congratulations');
                Route::post('/read/{subscriptionId}/{dayId}/note', [JovensPanelPlanReaderController::class, 'storeNote'])->name('reader.note.store');

                Route::get('/search', [JovensPanelBibleController::class, 'search'])->name('search');
                Route::get('/api/find', [JovensPanelBibleController::class, 'performSearch'])->name('api.search');
            });

            Route::get('/', [JovensPanelBibleController::class, 'index'])->name('index');
            Route::get('/interlinear', [InterlinearController::class, 'panelJovensIndex'])->name('interlinear');
            Route::get('/favorites', [JovensPanelBibleController::class, 'favorites'])->name('favorites');
            Route::post('/favorites/batch', [BibleFavoriteController::class, 'batchUpdate'])->name('favorites.batch');
            Route::post('/favorites/{id}', [BibleFavoriteController::class, 'toggle'])->name('favorites.toggle');
            Route::delete('/favorites/{id}', [BibleFavoriteController::class, 'destroy'])->name('favorites.destroy');

            Route::get('/verse/{verse}', [JovensPanelBibleController::class, 'verse'])->name('verse');
            Route::get('/ler/{version?}', [JovensPanelBibleController::class, 'read'])->name('read');
            Route::get('/{version}/livro/{book}', [JovensPanelBibleController::class, 'showBook'])->name('book');
            Route::get('/{version}/livro/{book}/capitulo/{chapter}', [JovensPanelBibleController::class, 'showChapter'])->name('chapter');
        });
    }

    if (module_enabled('Calendario')) {
        Route::prefix('calendario')->name('calendario.')->middleware(['permission:calendario.participate'])->group(function () {
            require module_path('Calendario', 'routes/participation.php');
        });
    }

    if (module_enabled('Talentos')) {
        Route::prefix('talentos')->name('talentos.')->middleware(['permission:talentos.profile.edit'])->group(function () {
            require module_path('Talentos', 'routes/talentos-panel.php');
        });
    }

    if (module_enabled('Avisos')) {
        Route::prefix('avisos')->name('avisos.')->group(function () {
            Route::get('/', [AvisosPainelController::class, 'index'])->name('index');
            Route::get('/{aviso}', [AvisosPainelController::class, 'show'])->name('show');
        });
    }

    if (module_enabled('Blog')) {
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/', [BlogPainelController::class, 'index'])->name('index');
            Route::get('/{slug}', [BlogPainelController::class, 'show'])->where('slug', '[^/]+')->name('show');
        });
    }
});
