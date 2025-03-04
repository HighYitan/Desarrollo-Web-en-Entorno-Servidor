<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('updated_at', 'DESC')->paginate(3); // Obtener todos los registros ordenados por updated_at DESC
        return view('user.index', ['users' => $users]); // Los mostramos con la View 
    }

    public function indexAntiguedad()
    {
        $users = User::orderBy('created_at', 'ASC')->paginate(3); // Obtener todos los registros ordenados por updated_at DESC
        return view('user.index', ['users' => $users]); // Los mostramos con la View 
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view('user.create'); // Llama a la vista create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*$user->save();  // Guarda el registro en la DDBB
        return redirect()->route('userCRUD.index');*/
    }

    /**
     * Display the specified resource.
     */
    public function show(User $userCRUD)
    {
        $comments = $userCRUD->comments()->orderBy('updated_at', 'DESC')->paginate(3); // Obtenemos los comentarios del usuario y los ordenamos por updated_at DESC
        return view('user.show',['user' => $userCRUD, 'comments' => $comments]); // Solo paso los comentarios aunque no haga falta para que solo muestre los comentarios en show y no en index para usar la misma card
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $userCRUD)
    {
        return view('user.edit', ['user' => $userCRUD]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarUserRequest $request, User $userCRUD)
    {
        $userCRUD->update($request->all()); //Actualizamos el registro de la DDBB

        return redirect()->route('userCRUD.index'); // Redirige a la página de inicio
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $userCRUD)
    {
        if($userCRUD->role->name == 'visitant'){ // Si el usuario es visitante
            foreach ($userCRUD->comments as $comment) { // Recorre los comentarios del usuario
                $comment->images()->delete(); // Borra las imágenes del comentario
                $comment->delete(); // Borra el comentario
            }
            $user->tokens()->delete(); // Revoca tots els tokens de l'usuari (/register i /login)
            $userCRUD->delete(); // Borra el usuario
        }
        return redirect()->route('userCRUD.index'); // Redirige a la página de inicio
    }
}
