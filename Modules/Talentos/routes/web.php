<?php

use Illuminate\Support\Facades\Route;

/*
| Página pública informativa (jovens/líderes inscrevem-se nos respetivos painéis).
*/
Route::view('/banco-de-talentos', 'talentos::public.index')->name('talentos.public');
