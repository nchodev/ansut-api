<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocalizationApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //check header request and determine localization
        $local = $request->hasHeader('x-localization')?(strlen($request->header('x-localization'))>0 ?$request->header('x-localization'):'fr'):'fr';
        // set Laravel localization
        if( in_array(strtolower($local),['fr','en','es'])){
            App::setlocale($local);
        }else
        {
            App::setlocale('fr');
        }


        return $next($request);
    }
}
