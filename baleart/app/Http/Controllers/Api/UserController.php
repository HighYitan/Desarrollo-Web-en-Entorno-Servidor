<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\GuardarUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // SELECCIÓ DE LES DADES
        $user->load([
            "spaces",
            "comments",
            "comments.images",
        ]);
        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuardarUserRequest $request, User $user)
    {
        $user->update($request->all());

        return (new UserResource($user))->additional(['meta' => 'Usuari modificat correctament']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Comprova si l'usuari té el rol "visitant"
        if ($user->role->name !== 'visitant') {
            return response()->json(['message' => 'Solo usuarios con el rol de visitant pueden ser eliminados'], 403);
        }
        // Borra los comentarios del usuario y sus respectivas imágenes
        foreach ($user->comments as $comment) {
            $comment->images()->delete(); // Borra las imágenes del comentario
            $comment->delete(); // Borra el comentario
        }

        // Revoca tots els tokens de l'usuari (/register i /login)
        $user->tokens()->delete();

        // ELIMINACIÓ DE LES DADES
        $user->delete();
        // SELECCIÓ DEL FORMAT DE LA RESPOSTA
        return (new UserResource($user))->additional(['meta' => 'Usuari eliminat correctament']);
    }
}
