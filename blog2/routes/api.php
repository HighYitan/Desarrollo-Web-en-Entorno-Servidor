<?php

use App\Http\Controllers\Api\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/
Route::apiresource('post', PostController::class); // contempla tots els mètodes a l'hora
Route::bind('post', function ($value) {
    return is_numeric($value)
        ? Post::findOrFail($value) // Cerca pel camp `id`
        : Post::where('title', $value)->firstOrFail(); // Cerca pel camp `title`
});

