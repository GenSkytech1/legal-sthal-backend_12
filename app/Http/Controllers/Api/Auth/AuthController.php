<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Cookie; 

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $response = $this->authService->register($request);

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);

        $response = $this->authService->login($request);

        if (isset($response['status']) && $response['status'] !== 200) {
            return response()->json([
                'error' => $response['error']
            ], $response['status']);
        }
      
        $cookie = cookie(
            'token',
            $response['token'],
            60 * 24 * 30,              // 1 month
            '/',
            null,
            app()->environment('production'), // secure = true in production (HTTPS)
            true,                 // httpOnly
            false,
            app()->environment('production') ? 'None' : 'Lax'
        );

        return response()->json([
            'token' => $response['token'],
            'user'  => $response['user']
        ])->cookie($cookie);
    }

    public function logout()
    {
        $response = $this->authService->logout();
        $cookie = Cookie::forget('token'); 
        return response()->json($response)->cookie($cookie);
    }

    public function me()
    {
        return response()->json(
            $this->authService->me()
        );
    }
}