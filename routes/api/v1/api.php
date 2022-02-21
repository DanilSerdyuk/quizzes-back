<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UsersController;

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
    /// QUIZZES ///
    Route::apiResource('quizzes', QuizController::class);
    Route::post('quizzes/assignee', [QuizController::class, 'assignee']);
    Route::get('quizzes/show/{id}', [QuizController::class, 'showById']);

    /// QUESTIONS ///
    Route::apiResource('questions', QuestionController::class)
        ->only(['store', 'update', 'destroy']);

    /// QUESTIONS ///
    Route::group(['prefix' => 'users', 'controller' => UsersController::class], function () {
        Route::get('/', 'index');
    });

    /// ANSWERS ///
    Route::group(['prefix' => 'answers', 'controller' => AnswerController::class], function () {
        Route::post('/', 'addAnswers');
    });
});
