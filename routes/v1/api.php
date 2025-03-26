<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout')->middleware('auth:api');
    Route::post('refresh', 'AuthController@refresh')->middleware('auth:api');
    Route::get('me', 'AuthController@me')->middleware('auth:api');

    Route::apiResource('/users', 'UserController')->only(['store', 'update']);
});

Route::apiResource('/tasks', 'TaskController')->middleware('auth:api');
