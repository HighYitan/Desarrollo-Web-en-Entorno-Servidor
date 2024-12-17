<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting( // Rutes presents a 'web.php' i 'api.php'
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        /*using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },*/
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middleware personalitzat
        /*$middleware->alias([
            'CheckRoleAdmin' => \App\Http\Middleware\CheckRoleAdmin::class,
            'ApiKeyMiddleware' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ]);
        
        // Aplicar middleware específic per a API
        $middleware->api("throttle:api");*/
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
