<?php

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\Api\V1\BibleController;

Route::get('/versions', [BibleController::class, 'versions'])->name('versions');
Route::get('/books', [BibleController::class, 'books'])->name('books');
Route::get('/chapters', [BibleController::class, 'chapters'])->name('chapters');
Route::get('/verses', [BibleController::class, 'verses'])->name('verses');
Route::get('/find', [BibleController::class, 'find'])->name('find');
Route::get('/search', [BibleController::class, 'search'])->name('search');
Route::get('/random', [BibleController::class, 'random'])->name('random');
Route::get('/compare', [BibleController::class, 'compare'])->name('compare');
Route::get('/audio-url', [BibleController::class, 'audioUrl'])->name('audio-url');
Route::get('/panorama', [BibleController::class, 'panorama'])->name('panorama');
