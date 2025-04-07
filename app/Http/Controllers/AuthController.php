<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->all());

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $authData = $this->authService->login($credentials);

        return response()->json([
            'message' => 'Login successful',
            'token' => $authData['token'],
            'user' => $authData['user']
        ]);

    }

    public function profile()
    {
        $user = $this->authService->profile();

        return response()->json([
            'user' => $user
        ]);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
