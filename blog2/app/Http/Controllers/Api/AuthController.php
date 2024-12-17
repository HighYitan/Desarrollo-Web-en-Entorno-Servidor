<?php

namespace App\Http\Controllers\Api;

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
          'name'      => 'required|string|max:255',
          'email'     => 'required|string|email|max:255|unique:users|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',
          'password'  => 'required|string|max:255'
        ]);
          
        if ($validator->fails()) {
          return response()->json($validator->errors());
        }
        
        $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'email_verified_at' => now(),
          'password' => Hash::make($request->password),
          'role' => 'user', //visitant a baleart
        ]);
  
        event(new Registered($user));
  
        $token = $user->createToken('auth_token')->plainTextToken;  // Crea el token en la taula 'personal_acces_tokens'
        
        return response()->json([
          'access_token' => $token,
          'token_type' => 'Bearer',
          'user' => $user,
          'status' => 'Register successful',
        ]);
    }
    public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'status' => 'Login successful',
        ]);
    }
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
