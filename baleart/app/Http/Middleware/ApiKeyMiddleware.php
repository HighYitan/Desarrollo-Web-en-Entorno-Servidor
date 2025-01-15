<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     * Maneja una petició entrant.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('x-api-key'); // Obtenir la clau API de la capçalera
        if ($apiKey === env('API_KEY')) { // Comprovar si la clau API és vàlida
            return $next($request);
        }

        if (Auth::guard('sanctum')->check()) { // Si la clau API no està present, utilitza el middleware Sanctum
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
