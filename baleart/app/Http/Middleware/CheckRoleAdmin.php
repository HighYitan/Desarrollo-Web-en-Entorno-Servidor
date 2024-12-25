<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*if (Auth::user()->role == 'admin') { //'Auth::user()' → classe de 'Breeze' que torna l'usuari connectat
            return $next($request);
        }*/
        if (Auth::user()->role->name == 'administrador' || Auth::user()->role->name == 'gestor') { //Autentica si un usuario es administrador o gestor para cuando hagamos BackOffice
            return $next($request);
        }
        abort(401);
        return redirect('/dashboard'); 
    }
}
