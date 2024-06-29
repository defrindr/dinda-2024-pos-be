<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'iam', 'middleware' => 'cors'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
