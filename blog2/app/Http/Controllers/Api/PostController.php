<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarPostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$posts = Post::all();
        // $posts = Post::paginate(3);  // crea una sortida amb paginació
        // $posts = Post::with([])->get();  // no té sentit
        //$posts = Post::with(["user", "category", "comments"])->get();  // post amb les taules relacionades, més óptima
        //$posts = Post::with(["user", "category", "comments", "comments.images"])->get();
        $query = Post::query();
        // Filtrar per 'name' si el paràmetre existeix a la consulta
        if ($request->has('titol')) {
            $query->where('title', 'like', '%' . $request->titol . '%');
        }
        // Filtrar per 'category' si el paràmetre existeix a la consulta
        if ($request->has('category')) {
            //$query = $query . ($query->where('category_id', $request->category));
            $query->where('category_id', $request->category); //Se concatenan las queries solas sin necesidad de nada más.
        }
        //$posts = POST::all() . $query->get();
        $query->with(['user', 'category', 'comments', 'comments.images']);
        $posts = $query->get();
        //return response()->json($posts);

        // $posts = Post::with(["user", "category", "comments", "comments.images"])->paginate(3);

        //return response()->json($posts);  // --> torna una resposta serialitzada en format 'json'
        return (PostResource::collection($posts))->additional(['meta' => 'Posts mostrats correctament']);  // torna una resposta personalitzada
    }

    /* 
    public function index_per_illa(string $illa)
    {
        $posts = Post::where('illa', $illa)->get();
        return response()->json($posts);

    }
    */

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // SELECCIÓ DE LES DADES
        // Post::find($post->id);  // no cal fer-ho, el model 'Post' ja ho fa de manera implícita
        // Post::find($id);  // no cal fer-ho, Laravel ja ho fa de manera implícita
       
        // AFEGINT DADES AMB 'load()'
        $post->load('user')->load('category')->load('comments')->load('comments.images');

        // AFEGINT DADES AMB 'with()'
        //$newPost = Post::with(["user","category","comments","comments.images"])->find($post->id);

        // SELECCIÓ DEL FORMAT DE LA RESPOSTA
       
        //return response()->json($post);
        // return response()->json($newPost);
        return (new PostResource($post))->additional(['meta' => 'Post mostrat correctament']);
        //return (new PostResource($newPost))->additional(['meta' => 'Post mostrat correctament']);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    public function store(GuardarPostRequest $request)
    {
        //si es id foranea hacer un where por name o por lo que sea más representativo
        // CREACIÓ DE LES DADES
        $post = Post::create(
            [   
                // Cal habilitar aquests atributs en Model->'$fillable'
                'title' => $request->title,
                'url_clean' => $request->url_clean,
                'content' => $request->content,
                'user_id' => $request->user_id,  // Auth::user()->id; (si s'empra la verificació d'usuari)
                'category_id' => $request->category_id,
            ]
        );
        // Post M:N Tags
        foreach ( explode(',', $request->tags) as $tag)
            $post->tags()->attach(Tag::firstOrCreate(['name' => trim($tag)])->id); // Tag::where( …)->get()->id

        // SELECCIÓ DEL FORMAT DE LA RESPOSTA
        // return response()->json(['meta' => 'Post creat correctament']);
        // return response()->json($post);
        // return response()->json([
        //return response()->json($post);
        return new PostResource($post);  
    }

    /**
     * Update the specified resource in storage.
     */
    //public function update(Request $request, string $id) original
    // public function update(PostRequest $request, Post $post)
    public function update(GuardarPostRequest $request, Post $post)
    {
        // Delete related records in the post_tag table
        $post->tags()->detach();
        
        // Post M:N Tags
        foreach ( explode(',', $request->tags) as $tag)
        $post->tags()->attach(Tag::firstOrCreate(['name' => trim($tag)])->id); // Tag::where( …)->get()->id

        // MODIFICACIÓ DE LES DADES
        $post->update($request->all());
        
        // SELECCIÓ DEL FORMAT DE LA RESPOSTA
        return (new PostResource($post))->additional(['meta' => 'Post modificat correctament']); //Se añade justo debajo de lo que hay en PostResource de meta
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete related records in the post_tag table
        $post->tags()->detach();
        // ELIMINACIÓ DE LES DADES
        $post->delete();

        // SELECCIÓ DEL FORMAT DE LA RESPOSTA
        return (new PostResource($post))->additional(['msg' => 'Post eliminat correctament']);
    }
    public function prova()    // PER EXEMPLE
    {
        return response()->json(['data' => 'Això és una prova']);
    }
}
