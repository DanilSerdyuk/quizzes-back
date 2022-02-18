<?php

use Illuminate\Support\Facades\Route;

Route::group(['controller' => \App\Http\Controllers\Auth\AuthController::class], function () {
    Route::post('login', 'login')->name('login');
    Route::post('registration', 'registration')->name('registration');

    Route::group(['middleware' => 'refresh.jwt'], function () {
        Route::post('refresh', 'refresh')->name('refresh');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'logout')->name('logout');
        Route::get('me', 'me')->name('me');
    });
});
