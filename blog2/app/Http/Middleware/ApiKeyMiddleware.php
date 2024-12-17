<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $apiKey = $request->header('x-api-key'); // Obtenir la clau des de les capçaleres
       
        if ($apiKey !== env('API_KEY')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request); // Continuar si la clau és vàlida
    }
}
