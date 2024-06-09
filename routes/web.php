<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\ApiAuthAnswerMiddleware;
use App\Http\Middleware\ApiAuthMiddleware;

Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/begin', [AuthController::class, 'registerAnsawer']);


// Rutas de administrador 
Route::group(['middleware' => ApiAuthMiddleware::class], function () {
    Route::post('/importFromJson', [SurveyController::class, 'importFromJson']);
});

// Rutas de usuario
Route::group(['middleware' => ApiAuthAnswerMiddleware::class], function () {
   // Route::post('/importFromJson', [SurveyController::class, 'importFromJson']);
});

