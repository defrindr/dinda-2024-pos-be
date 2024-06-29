<?php

use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\PelangganController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\SettingController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'master'], function () {
    Route::resource('category', CategoryController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('product', ProductController::class);
    Route::resource('user', UserController::class);

    // setting
    Route::get('setting', [SettingController::class, 'index']);
    Route::put('setting', [SettingController::class, 'update']);
});
