<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| Blog Public Routes
|--------------------------------------------------------------------------
*/

Route::prefix('blog')->name('blog.')->group(function () {
    // Homepage do blog
    Route::get('/', [BlogController::class, 'index'])->name('index');

    // Busca
    Route::get('/buscar', [BlogController::class, 'search'])->name('search');

    // Posts por categoria
    Route::get('/categoria/{slug}', [BlogController::class, 'category'])->name('category');

    // Posts por tag
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');

    // RSS Feed
    Route::get('/rss', [BlogController::class, 'rss'])->name('rss');

    // Sitemap
    Route::get('/sitemap.xml', [BlogController::class, 'sitemap'])->name('sitemap');

    // Post individual (deve vir por último para não capturar outras rotas)
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');

    // Comentários
    Route::post('/{postId}/comentarios', [BlogController::class, 'storeComment'])->name('comments.store');
});
