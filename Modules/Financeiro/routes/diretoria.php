<?php

use Illuminate\Support\Facades\Route;
use Modules\Financeiro\App\Http\Controllers\Diretoria\CategoryController;
use Modules\Financeiro\App\Http\Controllers\Diretoria\ExpenseRequestController;
use Modules\Financeiro\App\Http\Controllers\Diretoria\FinanceiroDashboardController;
use Modules\Financeiro\App\Http\Controllers\Diretoria\ObligationController;
use Modules\Financeiro\App\Http\Controllers\Diretoria\ReportController;
use Modules\Financeiro\App\Http\Controllers\Diretoria\TransactionController;

Route::prefix('financeiro')->name('financeiro.')->group(function () {
    Route::get('/', [FinanceiroDashboardController::class, 'index'])
        ->middleware('can:financeiro.dashboard.view')
        ->name('dashboard');

    Route::middleware('can:financeiro.reports.view')->group(function () {
        Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/relatorios/export.csv', [ReportController::class, 'csv'])->name('reports.export.csv');
        Route::get('/relatorios/export.pdf', [ReportController::class, 'pdf'])->name('reports.export.pdf');
    });

    Route::get('/obrigacoes', [ObligationController::class, 'index'])
        ->middleware('can:financeiro.obligations.view')
        ->name('obligations.index');
    Route::post('/obrigacoes/gerar-cotas', [ObligationController::class, 'generate'])
        ->middleware('can:financeiro.obligations.manage')
        ->name('obligations.generate');

    Route::resource('categories', CategoryController::class)
        ->parameters(['categories' => 'category'])
        ->except(['show']);

    Route::resource('transactions', TransactionController::class)
        ->parameters(['transactions' => 'transaction'])
        ->except(['show']);

    Route::resource('expense-requests', ExpenseRequestController::class)
        ->parameters(['expense-requests' => 'expense_request'])
        ->except(['show']);

    Route::post('/expense-requests/{expense_request}/submeter', [ExpenseRequestController::class, 'submit'])
        ->name('expense-requests.submit');
    Route::post('/expense-requests/{expense_request}/aprovar', [ExpenseRequestController::class, 'approve'])
        ->name('expense-requests.approve');
    Route::post('/expense-requests/{expense_request}/recusar', [ExpenseRequestController::class, 'reject'])
        ->name('expense-requests.reject');
    Route::post('/expense-requests/{expense_request}/pagar', [ExpenseRequestController::class, 'pay'])
        ->name('expense-requests.pay');
});
