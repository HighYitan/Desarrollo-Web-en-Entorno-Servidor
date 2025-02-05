<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarPostRequest;
use App\Http\Requests\ActualizarPostRequest;

class PostControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::all(); // Donde Post es la classe de la tabla posts all() es obtener todos los registros
        // $posts = Post::find(1); // Busca registro con la PK = 1
        // $posts = Post::find([1, 3]); // Busca registro con la PK = 1, PK = 3

        // Aplicamos un WHERE
        //$posts = Post::where('posted','=','not')->get(); // Where posted=not
        //$posts = Post::where('posted','not')->where('id','>',2)->get(); // Where (posted = not) AND (id > 2);
        //$posts = Post::where('posted2','not')->where('id','>',2)->get(); //Eror a propósito para ver la query que hace Laravel con Eloquent ya que el campo en la base de datos es posted, no posted2.
        // Genera un error SQLSTATE[42S22]: Column not found: 1054 Unknown column 'posted2' in 
        // 'where clause' (Connection: mysql, SQL: select * from `posts` where `posted2` = not and `id` > 2)

        //$posts = Post::where('posted','not')->orWhere('id','>',2)->get(); // Where (posted = not) OR (id > 2)
        /*$posts = Post::where('posted','yes') //select * from posts where posted = 'yes' or (posted = 'not' and category_id = 2);
        ->orwhere(function($query) {
            $query->where('posted','not')
            ->where('category_id','2');
        })->get();*/
        //$posts = Post::where('posted','not')->where('id','>',2)->first(); // Where (posted = not) OR (id > 2) y solo el primero
        //$posts = Post::where('posted','not')->orderBy('id','desc')->get(); // Ordenado por id de forma descendente
        //$posts = Post::select('title','url_clean','content')->get(); // Extracción de columnas específicas
        //$posts = Post::pluck('title','url_clean','content'); // Simplifica la salida, solamente los valores (Los dos primeros)
        //$posts =  Post::take(10)->skip(10)->get(); // De la 11 a la 20, es para paginar la salidad de la SELECT

        //dd($posts); // volcado del resultado

        //$posts = Post::all(); // Obtención de todas las publicaciones en $posts
        $posts = Post::paginate(3); // Devuelve el resultado de 3 en 3 publicaciones
        return view('post.index',['posts' => $posts]);  // Llamada a la View pasando $posts para maquetar el resultado del SQL
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ejemplos de SQL DIRECTO
        // $user = DB::select('select * from users');
        // $user = DB::select('select * from users where id = :id', ['id' => 1]); 

        // Ejemplos con el QUERY BUILDER 
        //$user = DB::table('users')->where('role','admin')->get(); // Ejemplo con where x = y
        //$user = DB::table('users')->where('role','!=','admin')->get(); // Ejemplo con where x != y

        //dd($user); // Muestra el resultado del select anterior 

    	return view('post.create'); // Llama a la vista create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuardarPostRequest $request)
    {
        // Si las validaciones son OK, entonces se debe proceder al insert en la DDBB
        $post = new Post; 

        $post->title = $request->title;
        $post->url_clean = $request->url_clean;  
        $post->content = $request->content; 
        $post->posted = 'not'; // Por defecto las publicaciones no están posteadas, requiren de supervisión
        $post->user_id = User::all()->random()->id; // Para que la FK user_id funcione, elegimos al azar
        $post->category_id = Category::all()->random()->id; // Para que la FK category_id funcione, elegimos al azar

        $post->save();

        //dd($request); // Desgrana el $request y lo pinta en pantalla

        // Validación de los input del formulario
        /*$request->validate([
            'title' => 'required|unique:posts|min:5|max:255',
        ]);*/

        //return back(); // Vuelve a la página anterior
        //return back()->with('status', 'Publicación creada correctamente'); // Vuelve a la página anterior con un mensaje informativo
        //return redirect()->route('postCRUD.index')->with('status','Publicación creada correctamente');
        return back()->with('status', '<h1>Publicación creada correctamente</h1>');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $postCRUD)
    {
        //$posts = Post::find($id); // Extrae regisro con PK = id
        //$posts = Post::findorfail($id); // Genera una respuesta http de error en caso de not found. Un 404
        //return view('post.show',['post' => $posts]);  // Recordar crear la vista
        return view('post.show',['post' => $postCRUD]);  // Porque el nombre del parámetro es así, postCRUD/{postCRUD}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $postCRUD)
    {
        return view('post.edit',['post' => $postCRUD]); // Llama a la vista post.edit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarPostRequest $request, Post $postCRUD)
    {
        /*$postCRUD->title = $request->title; // Actualiza el 'title' por el que viene en request
        $postCRUD->url_clean = $request->url_clean; // Actualiza la 'url_clean' por la que viene en request
        $postCRUD->content = $request->content; // Actualiza el 'content' por el que viene en request
    
        $postCRUD-> update(); //Actualizamos el registro de la DDBB*/
        $postCRUD->update($request->all()); //Actualizamos el registro de la DDBB 
        return back(); // Vuelve a la página origen, y vuelve a cargar el registro actualizado
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $postCRUD)
    {
        // Eliminación del registro 
        $postCRUD->delete(); 
        //return back();
        return back()->with('status', 'Publicación eliminada correctamente');
    }
}
