<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
 
     try {
         return response()->json([
                'message' => 'Unauthorized',
                'success' => false
            ], 401);
            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthorized',
                'success' => false
            ], 401);
        };
     

    }
}
