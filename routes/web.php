<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Middleware\ApiAuthAnswerMiddleware;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\AnswerController;

Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/begin', [AuthController::class, 'registerAnswer']);

// Rutas de administrador 
Route::group(['middleware' => ApiAuthMiddleware::class], function () {
    Route::post('/importFromJson', [SurveyController::class, 'importFromJson']);
});

// Registra tiempos por pagina 
Route::group(['middleware' => ApiAuthAnswerMiddleware::class], function () {
    Route::post('/timeStore', [AnswerController::class, 'TimeStore']);
    Route::post('/registerAnswer', [AnswerController::class, 'AnwersUpdate']);
});

