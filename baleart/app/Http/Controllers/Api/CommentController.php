<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
    public function show(string $id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuardarCommentRequest $request, Space $space)
    {
        $createdComments = [];
        $userId = Auth::guard('sanctum')->id(); // Obtenir l'ID de l'usuari autenticat
        if (!$userId) { // Si no hi ha usuari autenticat
            $user = User::where('email', $request->input('email'))->first(); // Busca l'usuari pel correu del JSON insertat
            if ($user) { // Si l'usuari existeix
                $userId = $user->id; // Assigna l'ID de l'usuari trobat
            } 
            else {
                return response()->json(['error' => 'No se encontró al usuario'], 404);
            }
        }
        // Iterate over the array of comments in the request
        foreach ($request->comentaris as $comment) {
            // Create each comment with the space_id and the authenticated user's ID
            $comentari = Comment::create([
                'comment' => $comment['comentari'],
                'score' => $comment['puntuació'],
                'space_id' => $space->id,
                'user_id' => $userId, // Usa el ID de l'usuari autenticat o el trobat
            ]);
            if (isset($comment['imatges']) && is_array($comment['imatges'])) { // Si hi ha imatges
                foreach ($comment['imatges'] as $imatge) { // Itera sobre les imatges del comentari
                    Image::create([
                        'url' => $imatge['imatge_url'],
                        'comment_id' => $comentari->id,
                    ]);
                }
            }
            $createdComments[] = $comentari; // Afegeix el comentari creat a l'array
            $createdCommentsCollection = Collection::make($createdComments); // Crea una col·lecció amb els comentaris creats
        }
        $space->calculateScores(); // Recalcula la puntuació del espai després d'afegir els comentaris
        $createdCommentsCollection->load(['space', 'user']); // Carrega les relacions dels comentaris creats per mostrar registre del espai i email de l'usuari
        return (CommentResource::collection($createdCommentsCollection))->additional(['meta' => 'Comentaris mostrats correctament']);
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
