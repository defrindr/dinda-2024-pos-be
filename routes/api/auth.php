<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'cors'], function () {
    Route::post('login', '\App\Http\Controllers\AuthController@login');
    // need auth
    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', '\App\Http\Controllers\AuthController@logout');
        Route::post('refresh', '\App\Http\Controllers\AuthController@refresh');
        Route::get('me', '\App\Http\Controllers\AuthController@me');
    });
});
