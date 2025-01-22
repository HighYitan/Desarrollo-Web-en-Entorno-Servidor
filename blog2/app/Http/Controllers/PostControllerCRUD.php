<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarPostRequest;

class PostControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
         $user = DB::table('users')->where('role','admin')->get(); // Ejemplo con where x = y
        // $user = DB::table('users')->where('role','!=','admin')->get(); // Ejemplo con where x != y

         dd($user); // Muestra el resultado del select anterior 

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

        return back(); // Vuelve a la página anterior 
 
        //dd($request); // Desgrana el $request y lo pinta en pantalla

        // Validación de los input del formulario
        /*$request->validate([
            'title' => 'required|unique:posts|min:5|max:255',
        ]);*/
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
