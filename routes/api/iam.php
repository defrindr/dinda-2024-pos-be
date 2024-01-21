<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'iam'], function () {
  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');
});
