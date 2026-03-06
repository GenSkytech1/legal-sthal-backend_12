<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\JwtFromCookie;
use Tymon\JWTAuth\Http\Middleware\Authenticate as JWTAuthenticate;
use Tymon\JWTAuth\Http\Middleware\RefreshToken; 

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.jwt'    => JWTAuthenticate::class,
            'jwt.refresh' => RefreshToken::class,
            'jwt.cookie'  => JwtFromCookie::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->errors()[array_key_first($e->errors())][0],
                    'errors'  => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });
    })->create();
