<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;


/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiresource('post', PostController::class); // contempla tots els mètodes a l'hora
    Route::apiResource('user', UserController::class); // Les tracta totes
    Route::bind('post', function ($value) {
        return is_numeric($value)
            ? Post::findOrFail($value) // Cerca pel camp `id`
            : Post::where('title', $value)->firstOrFail(); // Cerca pel camp `title`
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['ApiKeyMiddleware'])->group(function () {
    Route::get('/protected-route', function () {  // per exemple …
        return response()->json(['message' => 'Aquesta ruta està protegida per API Key']);
    });

    Route::apiResource('/post', PostController::class);  // Les tracta totes
});