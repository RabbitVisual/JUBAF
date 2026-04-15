<?php

use Illuminate\Support\Facades\Route;
use Modules\Secretaria\App\Http\Controllers\Diretoria\ConvocationController;
use Modules\Secretaria\App\Http\Controllers\Diretoria\DocumentController;
use Modules\Secretaria\App\Http\Controllers\Diretoria\MeetingController;
use Modules\Secretaria\App\Http\Controllers\Diretoria\MinuteController;
use Modules\Secretaria\App\Http\Controllers\Diretoria\SecretariaDashboardController;

Route::prefix('secretaria')->name('secretaria.')->group(function () {
    Route::get('/', [SecretariaDashboardController::class, 'index'])->name('dashboard');

    Route::resource('reunioes', MeetingController::class)
        ->parameters(['reunioes' => 'meeting']);

    Route::get('/atas/{minute}/anexos/{minute_attachment}/download', [MinuteController::class, 'attachmentDownload'])
        ->whereNumber(['minute', 'minute_attachment'])
        ->name('atas.attachments.download');
    Route::delete('/atas/{minute}/anexos/{minute_attachment}', [MinuteController::class, 'attachmentDestroy'])
        ->whereNumber(['minute', 'minute_attachment'])
        ->name('atas.attachments.destroy');
    Route::get('/atas/{minute}/pdf', [MinuteController::class, 'pdf'])->whereNumber('minute')->name('atas.pdf');
    Route::post('/atas/{minute}/submeter', [MinuteController::class, 'submit'])->whereNumber('minute')->name('atas.submit');
    Route::post('/atas/{minute}/assinar', [MinuteController::class, 'sign'])->whereNumber('minute')->name('atas.sign');
    Route::post('/atas/{minute}/arquivar', [MinuteController::class, 'archive'])->whereNumber('minute')->name('atas.archive');
    Route::resource('atas', MinuteController::class)
        ->parameters(['atas' => 'minute']);

    Route::post('/convocatorias/{convocation}/submeter', [ConvocationController::class, 'submit'])->name('convocatorias.submit');
    Route::post('/convocatorias/{convocation}/aprovar', [ConvocationController::class, 'approve'])->name('convocatorias.approve');
    Route::post('/convocatorias/{convocation}/publicar', [ConvocationController::class, 'publish'])->name('convocatorias.publish');
    Route::resource('convocatorias', ConvocationController::class)
        ->parameters(['convocatorias' => 'convocation']);

    Route::get('/arquivo/{document}/download', [DocumentController::class, 'download'])->name('arquivo.download');
    Route::resource('arquivo', DocumentController::class)
        ->only(['index', 'create', 'store', 'destroy'])
        ->parameters(['arquivo' => 'document']);
});
