<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'main'], function () {
    Route::get('dashboard/datacount', [DashboardController::class, 'dataCount']);

    // transaction
    Route::resource('transaction', TransactionController::class)->except(['update']);
    Route::get('transaction/print-struk/{invoiceCode}', [TransactionController::class, 'downloadInvoice']);
});
