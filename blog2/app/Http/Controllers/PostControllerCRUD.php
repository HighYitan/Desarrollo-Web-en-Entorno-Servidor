<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuardarPostRequest $request)
    {
        echo "Estoy en function store() de PostControllerCRUD<br>";

        echo 'Title'.$request->input('title').'<br>';
        echo 'Title'.$request->title.'<br>';
        echo 'Title'.request('title'); 

        //dd($request); // Desgrana el $request y lo pinta en pantalla

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
