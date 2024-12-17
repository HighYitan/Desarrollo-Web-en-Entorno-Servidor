<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;


/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiresource('post', PostController::class); // contempla tots els mètodes a l'hora
    Route::bind('post', function ($value) {
        return is_numeric($value)
            ? Post::findOrFail($value) // Cerca pel camp `id`
            : Post::where('title', $value)->firstOrFail(); // Cerca pel camp `title`
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

/*Route::apiresource('post', PostController::class); // contempla tots els mètodes a l'hora
Route::bind('post', function ($value) {
    return is_numeric($value)
        ? Post::findOrFail($value) // Cerca pel camp `id`
        : Post::where('title', $value)->firstOrFail(); // Cerca pel camp `title`
});*/

