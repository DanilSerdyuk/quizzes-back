<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('quizzes', \App\Http\Controllers\Api\QuizController::class);
    Route::post('quizzes/assignee', [\App\Http\Controllers\Api\QuizController::class, 'assignee']);
    Route::apiResource('questions', \App\Http\Controllers\Api\QuestionController::class)
        ->only(['store', 'update', 'destroy']);
});
