<?php

use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\PelangganController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'master'], function () {
  Route::resource('category', CategoryController::class);
  Route::resource('pelanggan', PelangganController::class);
  Route::resource('user', UserController::class);
});
