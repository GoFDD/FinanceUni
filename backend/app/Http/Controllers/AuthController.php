<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\DTOs\Auth\RegisterUserDTO;
use App\DTOs\Auth\LoginUserDTO;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = new RegisterUserDTO($request->validated());
        $user = $this->authService->register($dto);
        return response()->json(['user' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = new LoginUserDTO($request->validated());
        $token = $this->authService->login($dto);

        if (!$token) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        return response()->json(['token' => $token], 200);
    }
}

