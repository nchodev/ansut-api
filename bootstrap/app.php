<?php

use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            // API v1 - le middleware 'api' seul (sans 'auth:sanctum' global)
            Route::middleware('api')
                ->prefix('api/v1')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api/v1/api.php'));

            // API v2 - même approche
            Route::middleware('api')
                ->prefix('api/v2')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api/v2/api.php'));

            // Routes web
            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
            $middleware->alias([
                    'api.sanctum'=>ApiAuthMiddleware::class,
                     'abilities' => CheckAbilities::class,
                    // 'ability' => CheckForAnyAbility::class,
                ]);
      
    })
    ->withExceptions(function (Exceptions $exceptions) {
               $exceptions->render(function (AuthenticationException $e, Request $request) {
    
                return response()->json([
                    'success' => false,
                    'message' => $request->bearerToken() 
                        ? 'Token invalide ou expiré' 
                        : 'Authentification requise',
                    'error_code' => 'UNAUTHENTICATED'
                ], 401);
            
            
        });
        
    })->create();