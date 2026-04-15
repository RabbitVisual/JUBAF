<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\Api\BlogPostApiController;

Route::prefix('v1')->name('blog.v1.')->group(function () {
    Route::get('/posts', [BlogPostApiController::class, 'index'])->name('posts.index');
    Route::get('/posts/{slug}', [BlogPostApiController::class, 'show'])
        ->where('slug', '[^/]+')
        ->name('posts.show');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/posts', [BlogPostApiController::class, 'store'])->name('posts.store');
        Route::put('/posts/{post}', [BlogPostApiController::class, 'update'])->name('posts.update');
        Route::patch('/posts/{post}', [BlogPostApiController::class, 'update'])->name('posts.patch');
        Route::delete('/posts/{post}', [BlogPostApiController::class, 'destroy'])->name('posts.destroy');
    });
});
