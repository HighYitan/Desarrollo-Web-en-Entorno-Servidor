<?php

use App\Http\Controllers\CategoryControllerCRUD;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostControllerCRUD;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola',function() {
    return '<h1>Hola Mundo Cruel</h1>';
});

Route::get('/index.html',function() {

    $html_code = "<!DOCTYPE html>
                    <html>
                    <head>
                        <title>Laravel</title>
                    </head>
                    <body>
                        <h1>I love Laravel</h1>
                    </body>
                    </html>";

    return $html_code;
});

// Ejemplo de Route con un parámetro en la URI
Route::get('/hola/{nom}',function($nom) {

    return '<h1>Hola '.$nom.' estás en un Mundo Cruel</h1>';
})->name('holanom');  // Veremos esto más adelante, es un alias

Route::get('/hola2/{nom}',function($nom) {

    $html_code = "<!DOCTYPE html>
    <html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
        <h1>I love $nom </h1>
    </body>
    </html>";
    
    return $html_code;
});

Route::get('/hola3/{nom}/{professio?}',function($nom, $professio = null) { //hola3 para que no haga conflicto y muestre "Hola -nombre- que eres -nada-" al no escribir el segundo parámetro
	return '<h1>Hola '.$nom.' que eres '.$professio.' estás en un Mundo Cruel</h1>'; //hola para que haga conflicto
})->name('nomprof'); // Veremos esto más adelante, es un alias

Route::get('/holar/{nom}',function($nom) {
    return '<h1>Hola '.$nom.' estás en un Mundo Cruel</h1>';
})->where('nom','[A-Za-z]+');  // podemos poner whereAlpha('nom');

Route::get('/perfil/{id}',function($id) { // Route condicionada por plantilla
    return '<h3>Perfil Nº'.$id.'</h3>';
});

Route::get('/holanueva',function() {
    return '<h1>Hola Nueva</h1>';
})->name('salutacio'); // Alias, se usará más adelante

Route::get('/perfilr1/{id}',function($id) {
    return "<h3>Perfil Nº ".$id."<a href='/holanueva'>saluda a </a></h3>";
});

Route::get('/perfilr2/{id}',function($id) {
    return "<h3>Perfil Nº ".$id."<a href='".route('salutacio')."'>saluda a </a></h3>";
});

Route::get('/lñajalkjasljkasflkjasfd',function() {
    return '<h1>Hola de nuevo, ruta rara es lñajalkjasljkasflkjasfd</h1>';
})->name('rutarara');

Route::get('/perfilr3/{id}',function($id) {
    return "<h3>Perfil Nº".$id."<a href='".route('rutarara')."'>saluda</a></h3>";
});

// 	el parámetro se incluye mediante una array asociativo
Route::get('/perfilr4/{id}',function($id) {
    return "<h3>Perfil Nº".$id."<a href='".route('holanom',['nom'=>'Tommy'])."'>saluda</a></h3>";
});

Route::get('/perfilr5/{id}',function($id) {
    return "<h3>Perfil Nº".$id."<a href='".route('nomprof',['nom'=>'Tommy', 'professio'=>'Docent'])."'>saluda</a></h3>";
});

Route::group(['prefix'=>'admin','name'=>'admin'], function() {
    
    Route::get('/hola/{nom}',function($nom) {
        return '<h1>Hola '.$nom.' es agrupación</h1>';
    })->name('saluda'); //dando nombre a la ruta, ya la tomará de manera correcta '/admin/hola'

    Route::get('/usuari/{nom}', function ($nom) {
        return '<h1>Hola '.$nom.' es agrupación</h1>';
    })->name('user');
});

Route::get('/redireccion',function() {
    return "<h3>Perfil Nº <a href='".route('saluda',['nom'=>'Tommy'])."'>saluda</a></h3>";
});

Route::get('/usuaris/{usuari}', function(User $usuari){
    return $usuari;
});

Route::get('/posts/{post}', function(Post $post){
    return $post;
});

Route::get('/categories/{category}', function(Category $category){
    return $category;
});

// Observar que solamente extraerá un registro. 
/*Route::get('/posts2/{post:user_id}', function(Post $post){ //No lo hace nativamente, por defecto detecta id del post.
    return $post;
});*/

Route::get('/posts2/{user_id}', function($user_id){ // Este si muestra el primer post por la foreign key.
    $post = Post::where('user_id', $user_id)->firstOrFail();
    return $post;
});

Route::get('/perfilview/{nom}', function($nom) {
    return view('perfil', ['nom'=>$nom]);
}); 

Route::get('/perfilusuari/{usuari}', function(User $usuari) {
    return view('perfiluser',['user'=>$usuari]);
}); 

Route::get('/posts', [PostController::class, 'index']);

Route::resource('/postCRUD', PostControllerCRUD::class);

Route::resource('/categoryCRUD', CategoryControllerCRUD::class);
