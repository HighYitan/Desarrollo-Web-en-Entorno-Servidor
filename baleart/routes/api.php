<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SpaceController;
use App\Http\Controllers\Api\CommentController;

Route::post('/register', [AuthController::class, 'register']); // Registre d'usuaris
Route::post('/login', [AuthController::class, 'login']); // Iniciar sessió

Route::middleware('auth:sanctum')->group(function () { //Si ApiKeyMiddleware està activat no funcionaría así que hay que poner un if() en ese middleware concreto.
    
    Route::apiresource("space", SpaceController::class); // contempla tots els mètodes a l'hora
    Route::apiresource("user", UserController::class); // contempla tots els mètodes a l'hora
    Route::post('/comment/{space}', [CommentController::class, 'store']); // Crea comentaris amb imatges(Opcional) a un espai

    Route::post('/logout', [AuthController::class, 'logout']); // Tancar sessió (Eliminar token)
});

Route::middleware(['ApiKeyMiddleware'])->group(function () {
    Route::apiresource('space', SpaceController::class); // contempla tots els mètodes a l'hora
    Route::apiresource("user", UserController::class); // contempla tots els mètodes a l'hora
    Route::post('/comment/{space}', [CommentController::class, 'store']);
});
/*
    Ámbos middlewares funcionan independientemente gracias a los if que puse
    en el middleware ApiKeyMiddleware.php, solo se requiere uno para entrar a las rutas protegidas
    aunque se pueden usar ámbas autenticaciones a la vez y devuelve lo mismo sin problema.
*/