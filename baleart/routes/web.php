<?php

use App\Http\Controllers\CommentControllerCRUD;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpaceControllerCRUD;
use App\Http\Controllers\UserControllerCRUD;
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

    Route::get('/spaceCRUD/destacados', [SpaceControllerCRUD::class, 'indexDestacado'])->name('spaceCRUD.indexDestacado');
    //Route::get('/spaceCRUD/import', [SpaceControllerCRUD::class, 'importJsonForm'])->name('spaceCRUD.importJsonForm');
    //Route::post('/spaceCRUD/import', [SpaceControllerCRUD::class, 'importJson'])->name('spaceCRUD.importJson');
    Route::get('/spaceCRUD/export', [SpaceControllerCRUD::class, 'exportJson'])->name('spaceCRUD.exportJson');
    Route::resource('/spaceCRUD', SpaceControllerCRUD::class); // Genera todas la Route para el Controller de Space

    Route::get('/userCRUD/antiguedad', [UserControllerCRUD::class, 'indexAntiguedad'])->name('userCRUD.indexAntiguedad');
    Route::resource('/userCRUD', UserControllerCRUD::class); // Genera todas la Route para el Controller de User

    Route::resource('/commentCRUD', CommentControllerCRUD::class); // Genera todas la Route para el Controller de Comment
});

require __DIR__.'/auth.php';
