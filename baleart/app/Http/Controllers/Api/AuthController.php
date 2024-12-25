<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
          'nom'         => 'required|string|max:100|min:2', //El nombre más corto del mundo, 2 caracteres
          'cognom'      => 'required|string|max:100|min:2',
          'email'       => 'required|string|email|max:100|min:6|unique:users|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',
          'telèfon'     => 'required|string|max:100|min:7',//Es el número de teléfono más corto del mundo, 7 dígitos
          'contrasenya' => 'required|string|max:100|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/', //Necesita minuscula, mayuscula, número y caracter especial
        ]);
          
        if ($validator->fails()) {
          return response()->json($validator->errors());
        }
        $role = Role::where('name', 'visitant')->firstOrFail(); //Busca el rol 'visitant' en la tabla 'roles'
        $user = User::create([
          'name' => $request->nom,
          'lastName' => $request->cognom,
          'email' => $request->email,
          'email_verified_at' => now(),
          'phone' => $request->telèfon,
          'password' => Hash::make($request->contrasenya),
          'role_id' => $role->id, //visitant a baleart
        ]);
  
        event(new Registered($user));
  
        $token = $user->createToken('auth_token')->plainTextToken;  // Crea el token en la taula 'personal_acces_tokens'
        
        return response()->json([
          'acces_token' => $token,
          'tipus_token' => 'Bearer',
          "nom" => $user->name,
          "cognom" => $user->lastName,
          'email' => $user->email,
          'telèfon' => $user->phone,
          "rol" => $role->name,
          //'user' => $user, //Si quisiéramos mostrar todos los datos del usuario de forma rònica.
          'status' => 'Registro completado',
        ]);
    }
    public function login(Request $request) {
        $request->merge([
            'password' => $request->contrasenya, //Mapea el campo contrasenya a password para que pueda ser validado
        ]);
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'contrasenya' => ['required', 'string'],
        ]);
        
        if (!Auth::attempt($request->only('email', 'password'))) { //Se valida el email y la contraseña
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'acces_token' => $token,
            'tipus_token' => 'Bearer',
            "nom" => $user->name,
            "cognom" => $user->lastName,
            'email' => $user->email,
            'telèfon' => $user->phone,
            "rol" => $user->role->name,
            //'user' => $user, //Si quisiéramos mostrar todos los datos del usuario de forma rònica.
            'status' => 'Login completado',
        ]);
    }
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete(); //Elimina el token de /login de la tabla 'personal_access_tokens'

        return response()->json([
            "nom" => $request->user()->name,
            "cognom" => $request->user()->lastName,
            'email' => $request->user()->email,
            'telèfon' => $request->user()->phone,
            "rol" => $request->user()->role->name,
            'status' => 'Logout completado',
        ]);
    }
}