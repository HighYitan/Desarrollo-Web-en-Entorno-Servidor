<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentControllerCRUD extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $commentCRUD)
    {
        return view('comment.show',['comment' => $commentCRUD]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $commentCRUD)
    {
        return view('comment.edit', ['comment' => $commentCRUD]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarCommentRequest $request, Comment $commentCRUD)
    {
        $commentCRUD->update($request->all()); //Actualizamos el registro de la DDBB
        // puntuacióMitjana de 4 o más = destacado
        $commentCRUD->space->calculateScores(); // Recalcula la puntuación total y el número de puntuaciones del espacio al que pertenece el comentario

        return redirect()->route('userCRUD.show', ['userCRUD' => $commentCRUD->user_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
