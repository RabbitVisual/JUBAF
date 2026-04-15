<?php

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\InterlinearController;
use Modules\Bible\App\Http\Controllers\PublicBibleController;

Route::prefix('biblia')->name('bible.public.')->group(function (): void {
    Route::get('/', [PublicBibleController::class, 'index'])->name('index');
    Route::get('/buscar', [PublicBibleController::class, 'search'])->name('search');
    Route::get('/versao/{versionAbbr?}', [PublicBibleController::class, 'read'])
        ->where('versionAbbr', '[A-Za-z0-9._-]+')
        ->name('read');
    Route::get('/versao/{versionAbbr}/livro/{bookNumber}', [PublicBibleController::class, 'book'])
        ->where(['versionAbbr' => '[A-Za-z0-9._-]+', 'bookNumber' => '[0-9]+'])
        ->name('book');
    Route::get('/versao/{versionAbbr}/livro/{bookNumber}/capitulo/{chapterNumber}', [PublicBibleController::class, 'chapter'])
        ->where(['versionAbbr' => '[A-Za-z0-9._-]+', 'bookNumber' => '[0-9]+', 'chapterNumber' => '[0-9]+'])
        ->name('chapter');

    Route::get('/interlinear', [InterlinearController::class, 'index'])->name('interlinear');
    Route::get('/interlinear/versoes', [InterlinearController::class, 'versions'])->name('interlinear.versions');
    Route::get('/interlinear/livros', [InterlinearController::class, 'getBooksMetadata'])->name('interlinear.books');
    Route::get('/interlinear/dados', [InterlinearController::class, 'getData'])->name('interlinear.data');
    Route::get('/interlinear/strong/{number}', [InterlinearController::class, 'getStrongDefinition'])->name('interlinear.strong');
    Route::get('/interlinear/ocorrencias/{number}', [InterlinearController::class, 'getStrongOccurrences'])->name('interlinear.occurrences');
});
