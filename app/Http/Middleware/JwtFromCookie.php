<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->header('Authorization') && $request->cookie('token')) {
            $request->headers->set(
                'Authorization',
                'Bearer ' . $request->cookie('token')
            );
        }
        return $next($request);
    }
}
