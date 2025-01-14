<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryControllerCRUD;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola', function () {
    return 'Hola mundo cruel';
});

Route::resource('/categoryCRUD', CategoryControllerCRUD::class);
