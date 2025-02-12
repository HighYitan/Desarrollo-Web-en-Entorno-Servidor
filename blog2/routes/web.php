<?php

use App\Http\Controllers\CategoryControllerCRUD;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostControllerCRUD;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //////////////// Routes del CRUD
    Route::resource('/postCRUD', PostControllerCRUD::class); // Genera automáticamente todas las rutas para el controllador PostControllerCRUD
    Route::post('/posts/{post}/edit/images',[PostControllerCRUD::class, 'image'])->name('post.image'); // Para imágenes

    Route::resource('/categoryCRUD', CategoryControllerCRUD::class);

});

require __DIR__.'/auth.php';