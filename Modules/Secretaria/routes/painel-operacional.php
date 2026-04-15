<?php

use Illuminate\Support\Facades\Route;
use Modules\Secretaria\App\Http\Controllers\Operacional\SecretariaLeituraController;

Route::get('/', [SecretariaLeituraController::class, 'index'])->name('index');
Route::get('/atas', [SecretariaLeituraController::class, 'minutes'])->name('atas.index');
Route::get('/atas/{minute}/anexos/{minute_attachment}/download', [SecretariaLeituraController::class, 'minuteAttachmentDownload'])
    ->whereNumber(['minute', 'minute_attachment'])
    ->name('atas.attachments.download');
Route::get('/atas/{minute}/pdf', [SecretariaLeituraController::class, 'minutePdf'])->whereNumber('minute')->name('atas.pdf');
Route::get('/atas/{minute}', [SecretariaLeituraController::class, 'minuteShow'])->whereNumber('minute')->name('atas.show');
Route::get('/convocatorias', [SecretariaLeituraController::class, 'convocations'])->name('convocatorias.index');
Route::get('/convocatorias/{convocation}', [SecretariaLeituraController::class, 'convocationShow'])->whereNumber('convocation')->name('convocatorias.show');
Route::get('/documentos', [SecretariaLeituraController::class, 'documents'])->name('documentos.index');
Route::get('/documentos/{document}/download', [SecretariaLeituraController::class, 'documentDownload'])->whereNumber('document')->name('documentos.download');
