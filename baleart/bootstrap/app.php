<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckRoleAdmin;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting( // Rutes presents a 'web.php' i 'api.php'
        /*web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',*/
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middleware personalitzat
        $middleware->alias([
            'CheckRoleAdmin' => \App\Http\Middleware\CheckRoleAdmin::class,
            'ApiKeyMiddleware' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ]);
        
        // Aplicar middleware específic per a API
        $middleware->api("throttle:api"); // Limitar peticions a API
        //$middleware->api("auth:sanctum");
        $middleware->api("ApiKeyMiddleware"); // Middleware personalitzat que maneja API Key y Token sanctum
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejar excepcions per a rutes API
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'No hem trobat elements.'
                ], 404);
            }
        });
    })->create();
