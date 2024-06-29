<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'main', 'middleware' => 'cors'], function () {
    Route::get('dashboard/datacount', [DashboardController::class, 'dataCount']);
    Route::get('transaction/report', [TransactionController::class, 'report']);
    Route::get('transaction/export-excel', [TransactionController::class, 'reportExcel']);

    // transaction
    Route::resource('transaction', TransactionController::class)->except(['update']);
    Route::get('transaction/print-struk/{invoiceCode}', [TransactionController::class, 'downloadInvoice']);
});
