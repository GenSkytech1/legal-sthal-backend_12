<?php

namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register($request)
    {
        $user = User::create([
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'timesite' => $request->timesite,
            'created_by' => auth()->id(),
        ]);

        return [
            'message' => 'User created successfully',
            'user' => $user
        ];
    }

    public function login($request)
    {
        $credentials = $request->only('user_name', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return [
                'error' => 'Invalid Credentials',
                'status' => 401
            ];
        }

        return [
            'token' => $token,
            'user' => auth()->user(),
            'status' => 200
        ];
    }

    public function logout()
    {
        auth()->logout();

        return [
            'message' => 'Logged out successfully'
        ];
    }

    public function me()
    {
        return auth()->user();
    }
}