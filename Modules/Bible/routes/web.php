<?php

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\BibleController;
use Modules\Bible\App\Http\Controllers\InterlinearController;
use Modules\Bible\App\Http\Controllers\PainelJovens\BibleController as MemberBibleController;
use Modules\Bible\App\Http\Controllers\PainelJovens\FavoriteController as MemberFavoriteController;
use Modules\Bible\App\Http\Controllers\PainelJovens\PlanReaderController as MemberPlanReaderController;
use Modules\Bible\App\Http\Controllers\PainelJovens\ReadingPlanController as MemberReadingPlanController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('bibles/compare', [BibleController::class, 'compare'])->name('bible.compare');
    Route::resource('bibles', BibleController::class)->names('bible.web');

    // Admin Routes (admin/bible plans, import, api) → centralizadas em routes/admin.php

    // Leitor social (URLs canónicas member.bible.* — registar antes de rotas com {version})
    Route::prefix('social/bible/plans')->name('member.bible.')->group(function () {
        Route::get('/', [MemberReadingPlanController::class, 'index'])->name('plans.index');
        Route::get('/catalog', [MemberReadingPlanController::class, 'catalog'])->name('plans.catalog');
        Route::get('/{id}/preview', [MemberReadingPlanController::class, 'preview'])->whereNumber('id')->name('plans.preview');
        Route::post('/{id}/subscribe', [MemberReadingPlanController::class, 'subscribe'])->whereNumber('id')->name('plans.subscribe');
        Route::get('/resume/{id}', [MemberReadingPlanController::class, 'show'])->whereNumber('id')->name('plans.show');
        Route::post('/{subscriptionId}/recalculate', [MemberReadingPlanController::class, 'recalculate'])->whereNumber('subscriptionId')->name('plans.recalculate');
        Route::get('/download/{id}/pdf', [MemberReadingPlanController::class, 'downloadPdf'])->whereNumber('id')->name('plans.pdf');

        // Reader
        Route::get('/read/{subscriptionId}/{day}', [MemberPlanReaderController::class, 'read'])->name('reader');
        Route::post('/read/{subscriptionId}/{dayId}/complete', [MemberPlanReaderController::class, 'complete'])->name('reader.complete');
        Route::post('/read/{subscriptionId}/{dayId}/uncomplete', [MemberPlanReaderController::class, 'uncomplete'])->name('reader.uncomplete');
        Route::get('/read/{subscriptionId}/{dayId}/congratulations', [MemberPlanReaderController::class, 'congratulations'])->name('reader.congratulations');
        Route::post('/read/{subscriptionId}/{dayId}/note', [MemberPlanReaderController::class, 'storeNote'])->name('reader.note.store');

        // Search (API para o leitor de planos / busca social)
        Route::get('/search', [MemberBibleController::class, 'search'])->name('search');
        Route::get('/api/find', [MemberBibleController::class, 'performSearch'])->name('api.search');
    });

    Route::prefix('social/bible')->name('member.bible.')->group(function () {
        Route::get('/', [MemberBibleController::class, 'index'])->name('index');
        Route::get('/interlinear', [InterlinearController::class, 'panelIndex'])->name('interlinear');
        Route::get('/favorites', [MemberBibleController::class, 'favorites'])->name('favorites');
        Route::get('/verse/{verse}', [MemberBibleController::class, 'verse'])->name('verse');
        Route::get('/read/{version?}', [MemberBibleController::class, 'read'])->name('read');
        Route::get('/{version}/livro/{book}', [MemberBibleController::class, 'showBook'])->name('book');
        Route::get('/{version}/livro/{book}/capitulo/{chapter}', [MemberBibleController::class, 'showChapter'])->name('chapter');
    });

    // Bible Favorites (Highlights)
    Route::prefix('social/bible/favorites')->name('member.bible.favorites.')->group(function () {
        Route::post('/batch', [MemberFavoriteController::class, 'batchUpdate'])->name('batch');
        Route::post('/{id}', [MemberFavoriteController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [MemberFavoriteController::class, 'destroy'])->name('destroy');
    });
});
