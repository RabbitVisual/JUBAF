<?php

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\InterlinearController;
use Modules\Bible\App\Http\Controllers\PainelJovens\FavoriteController as BibleFavoriteController;
use Modules\Bible\App\Http\Controllers\PainelLider\BibleController as LiderBibleController;
use Modules\Bible\App\Http\Controllers\PainelLider\PlanReaderController as LiderPlanReaderController;
use Modules\Bible\App\Http\Controllers\PainelLider\ReadingPlanController as LiderReadingPlanController;
use Modules\Avisos\App\Http\Controllers\AvisosPainelController;
use Modules\Blog\App\Http\Controllers\BlogPainelController;
use Modules\PainelLider\App\Http\Controllers\ChatController;
use Modules\Igrejas\App\Http\Controllers\PainelLider\CongregacaoController;
use Modules\Igrejas\App\Http\Controllers\PainelLider\CongregacaoJovensController;
use Modules\PainelLider\App\Http\Controllers\DashboardController;
use Modules\PainelLider\App\Http\Controllers\NotificacoesController as LiderNotificacoesController;
use App\Http\Controllers\Profile\DataChangeRequestController;
use Modules\PainelLider\App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Painel de Líderes JUBAF — rotas canónicas (lógica no módulo PainelLider)
|--------------------------------------------------------------------------
*/

Route::permanentRedirect('/campo', '/lideres');
Route::permanentRedirect('/campo/dashboard', '/lideres/dashboard');

Route::prefix('lideres')->name('lideres.')->middleware(['auth', 'role:lider', 'lider.panel'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/filtros', [DashboardController::class, 'filtros'])->name('dashboard.filtros');
    Route::get('/dashboard/estatisticas', [DashboardController::class, 'estatisticas'])->name('dashboard.estatisticas');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/solicitar-dados-sensiveis', [DataChangeRequestController::class, 'store'])->name('profile.sensitive-data-request.store');

    if (module_enabled('Notificacoes')) {
        Route::get('/notificacoes', [LiderNotificacoesController::class, 'index'])->name('notificacoes.index');
        Route::get('/notificacoes/{id}', [LiderNotificacoesController::class, 'show'])->whereNumber('id')->name('notificacoes.show');
    }

    if (module_enabled('Igrejas')) {
        Route::get('/congregacao', [CongregacaoController::class, 'index'])->name('congregacao.index');

        Route::prefix('congregacao/jovens')
            ->name('congregacao.jovens.')
            ->middleware(['permission:igrejas.jovens.provision'])
            ->group(function () {
                Route::get('/create', [CongregacaoJovensController::class, 'create'])->name('create');
                Route::post('/', [CongregacaoJovensController::class, 'store'])->name('store');
                Route::get('/{youth}/edit', [CongregacaoJovensController::class, 'edit'])->name('edit');
                Route::put('/{youth}', [CongregacaoJovensController::class, 'update'])->name('update');
                Route::post('/{youth}/enviar-link-palavra-passe', [CongregacaoJovensController::class, 'sendPasswordReset'])->name('send-password-reset');
            });

        Route::prefix('igrejas')->name('igrejas.')
            ->middleware(['permission:igrejas.requests.submit'])
            ->group(function () {
                require module_path('Igrejas', 'routes/painel-lider.php');
            });
    }

    if (module_enabled('Financeiro')) {
        Route::prefix('financeiro')->name('financeiro.')
            ->middleware(['permission:financeiro.minhas_contas.view'])
            ->group(function () {
                require module_path('Financeiro', 'routes/painel-lider.php');
            });
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
                return view('chat::painellider.chat.index');
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
                Route::get('/', [LiderReadingPlanController::class, 'index'])->name('plans.index');
                Route::get('/catalog', [LiderReadingPlanController::class, 'catalog'])->name('plans.catalog');
                Route::get('/{id}/preview', [LiderReadingPlanController::class, 'preview'])->whereNumber('id')->name('plans.preview');
                Route::post('/{id}/subscribe', [LiderReadingPlanController::class, 'subscribe'])->whereNumber('id')->name('plans.subscribe');
                Route::get('/resume/{id}', [LiderReadingPlanController::class, 'show'])->whereNumber('id')->name('plans.show');
                Route::post('/{subscriptionId}/recalculate', [LiderReadingPlanController::class, 'recalculate'])->whereNumber('subscriptionId')->name('plans.recalculate');
                Route::get('/download/{id}/pdf', [LiderReadingPlanController::class, 'downloadPdf'])->whereNumber('id')->name('plans.pdf');

                Route::get('/read/{subscriptionId}/{day}', [LiderPlanReaderController::class, 'read'])->name('reader');
                Route::post('/read/{subscriptionId}/{dayId}/complete', [LiderPlanReaderController::class, 'complete'])->name('reader.complete');
                Route::post('/read/{subscriptionId}/{dayId}/uncomplete', [LiderPlanReaderController::class, 'uncomplete'])->name('reader.uncomplete');
                Route::get('/read/{subscriptionId}/{dayId}/congratulations', [LiderPlanReaderController::class, 'congratulations'])->name('reader.congratulations');
                Route::post('/read/{subscriptionId}/{dayId}/note', [LiderPlanReaderController::class, 'storeNote'])->name('reader.note.store');

                Route::get('/search', [LiderBibleController::class, 'search'])->name('search');
                Route::get('/api/find', [LiderBibleController::class, 'performSearch'])->name('api.search');
            });

            Route::get('/', [LiderBibleController::class, 'index'])->name('index');
            Route::get('/interlinear', [InterlinearController::class, 'panelLiderIndex'])->name('interlinear');
            Route::get('/favorites', [LiderBibleController::class, 'favorites'])->name('favorites');
            Route::post('/favorites/batch', [BibleFavoriteController::class, 'batchUpdate'])->name('favorites.batch');
            Route::post('/favorites/{id}', [BibleFavoriteController::class, 'toggle'])->name('favorites.toggle');
            Route::delete('/favorites/{id}', [BibleFavoriteController::class, 'destroy'])->name('favorites.destroy');

            Route::get('/verse/{verse}', [LiderBibleController::class, 'verse'])->name('verse');
            Route::get('/ler/{version?}', [LiderBibleController::class, 'read'])->name('read');
            Route::get('/{version}/livro/{book}', [LiderBibleController::class, 'showBook'])->name('book');
            Route::get('/{version}/livro/{book}/capitulo/{chapter}', [LiderBibleController::class, 'showChapter'])->name('chapter');
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
