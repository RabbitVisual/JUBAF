<?php

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\Admin\BibleAdminLookupController;
use Modules\Bible\App\Http\Controllers\Admin\BibleCommentaryAdminController;
use Modules\Bible\App\Http\Controllers\Admin\BibleController;
use Modules\Bible\App\Http\Controllers\Admin\BibleCrossReferenceAdminController;
use Modules\Bible\App\Http\Controllers\Admin\BiblePlanController;
use Modules\Bible\App\Http\Controllers\Admin\BibleReportController;
use Modules\Bible\App\Http\Controllers\Admin\BibleStrongsLexiconController;
use Modules\Bible\App\Http\Controllers\InterlinearController;

/*
|--------------------------------------------------------------------------
| Bíblia digital (painel /admin) — nomes de rota admin.bible.*
|--------------------------------------------------------------------------
| URL base: /admin/biblia-digital (registo em routes/admin.php).
*/

Route::get('/relatorios/plano-igreja', [BibleReportController::class, 'churchPlan'])->name('reports.church-plan');

Route::get('/ferramentas/leitor-interlinear', [InterlinearController::class, 'panelBibleAdminIndex'])
    ->name('tools.interlinear');

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/livros', [BibleAdminLookupController::class, 'books'])->name('books');
    Route::get('/capitulos', [BibleAdminLookupController::class, 'chapters'])->name('chapters');
    Route::get('/versiculos', [BibleAdminLookupController::class, 'verses'])->name('verses');
});

Route::prefix('estudo')->name('study.')->group(function () {
    Route::prefix('strongs')->name('strongs.')->group(function () {
        Route::get('/', [BibleStrongsLexiconController::class, 'index'])->name('index');
        Route::get('/{strong_number}/editar', [BibleStrongsLexiconController::class, 'edit'])
            ->where('strong_number', '[A-Za-z0-9]+')
            ->name('edit');
        Route::put('/{strong_number}', [BibleStrongsLexiconController::class, 'update'])
            ->where('strong_number', '[A-Za-z0-9]+')
            ->name('update');
    });

    Route::prefix('comentarios/fontes')->name('commentary.sources.')->group(function () {
        Route::get('/', [BibleCommentaryAdminController::class, 'sourcesIndex'])->name('index');
        Route::get('/{id}/editar', [BibleCommentaryAdminController::class, 'sourcesEdit'])->whereNumber('id')->name('edit');
        Route::put('/{id}', [BibleCommentaryAdminController::class, 'sourcesUpdate'])->whereNumber('id')->name('update');
    });

    Route::prefix('comentarios/entradas')->name('commentary.entries.')->group(function () {
        Route::get('/', [BibleCommentaryAdminController::class, 'entriesIndex'])->name('index');
        Route::get('/{id}/editar', [BibleCommentaryAdminController::class, 'entriesEdit'])->whereNumber('id')->name('edit');
        Route::put('/{id}', [BibleCommentaryAdminController::class, 'entriesUpdate'])->whereNumber('id')->name('update');
    });

    Route::prefix('refs-cruzadas')->name('cross-refs.')->group(function () {
        Route::get('/', [BibleCrossReferenceAdminController::class, 'index'])->name('index');
        Route::get('/{id}/editar', [BibleCrossReferenceAdminController::class, 'edit'])->whereNumber('id')->name('edit');
        Route::put('/{id}', [BibleCrossReferenceAdminController::class, 'update'])->whereNumber('id')->name('update');
        Route::delete('/{id}', [BibleCrossReferenceAdminController::class, 'destroy'])->whereNumber('id')->name('destroy');
    });
});

Route::prefix('planos')->name('plans.')->group(function () {
    Route::get('/', [BiblePlanController::class, 'index'])->name('index');
    Route::get('/criar', [BiblePlanController::class, 'create'])->name('create');
    Route::post('/', [BiblePlanController::class, 'store'])->name('store');
    Route::get('/{id}/gerar', [BiblePlanController::class, 'generator'])->whereNumber('id')->name('generate');
    Route::post('/{id}/gerar', [BiblePlanController::class, 'processGeneration'])->whereNumber('id')->name('process-generation');
    Route::get('/{planId}/dia/{dayId}/editar', [BiblePlanController::class, 'editDay'])
        ->whereNumber('planId')
        ->whereNumber('dayId')
        ->name('days.edit');
    Route::post('/dia/{dayId}/conteudo', [BiblePlanController::class, 'storeContent'])->whereNumber('dayId')->name('content.store');
    Route::put('/conteudo/{contentId}', [BiblePlanController::class, 'updateContent'])->whereNumber('contentId')->name('content.update');
    Route::delete('/conteudo/{contentId}', [BiblePlanController::class, 'destroyContent'])->whereNumber('contentId')->name('content.destroy');
    Route::get('/{id}/editar', [BiblePlanController::class, 'edit'])->whereNumber('id')->name('edit');
    Route::get('/{id}', [BiblePlanController::class, 'show'])->whereNumber('id')->name('show');
    Route::put('/{id}', [BiblePlanController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{id}', [BiblePlanController::class, 'destroy'])->whereNumber('id')->name('destroy');
});

Route::get('/importar', [BibleController::class, 'importForm'])->name('import');
Route::post('/importar', [BibleController::class, 'store'])->name('import.store');

Route::get('/', [BibleController::class, 'index'])->name('index');
Route::get('/criar', [BibleController::class, 'create'])->name('create');
Route::post('/', [BibleController::class, 'store'])->name('store');

Route::get('/{bible}/audio-capitulos/modelo', [BibleController::class, 'chapterAudioTemplate'])->whereNumber('bible')->name('chapter-audio.template');
Route::get('/{bible}/audio-capitulos', [BibleController::class, 'chapterAudioIndex'])->whereNumber('bible')->name('chapter-audio.index');
Route::post('/{bible}/audio-capitulos', [BibleController::class, 'chapterAudioStore'])->whereNumber('bible')->name('chapter-audio.store');
Route::delete('/{bible}/audio-capitulos/{chapter_audio}', [BibleController::class, 'chapterAudioDestroy'])
    ->whereNumber('bible')
    ->whereNumber('chapter_audio')
    ->name('chapter-audio.destroy');

Route::get('/{version}/livro/{book}/capitulo/{chapter}', [BibleController::class, 'viewChapter'])
    ->whereNumber('version')
    ->whereNumber('book')
    ->whereNumber('chapter')
    ->name('chapter');

Route::get('/{version}/livro/{book}', [BibleController::class, 'viewBook'])
    ->whereNumber('version')
    ->whereNumber('book')
    ->name('book');

Route::get('/{bible}/editar', [BibleController::class, 'edit'])->whereNumber('bible')->name('edit');
Route::get('/{bible}', [BibleController::class, 'show'])->whereNumber('bible')->name('show');
Route::put('/{bible}', [BibleController::class, 'update'])->whereNumber('bible')->name('update');
Route::delete('/{bible}', [BibleController::class, 'destroy'])->whereNumber('bible')->name('destroy');
