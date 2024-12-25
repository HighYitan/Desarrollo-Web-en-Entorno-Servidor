<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () { //No funciona si ApiKeyMiddleware està activat así que hay que poner un if en ese middleware concreto.
    
    Route::apiresource('post', PostController::class); // contempla tots els mètodes a l'hora
    Route::apiResource('user', UserController::class); // Les tracta totes
    Route::apiResource('category', CategoryController::class); // Les tracta totes

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['ApiKeyMiddleware'])->group(function () {

    Route::apiresource('post', PostController::class); // contempla tots els mètodes a l'hora
    Route::apiResource('user', UserController::class); // Les tracta totes
    Route::apiResource('category', CategoryController::class); // Les tracta totes

    Route::post('/logout', [AuthController::class, 'logout']);
});