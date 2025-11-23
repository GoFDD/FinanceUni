<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\DTOs\Auth\RegisterUserDTO;
use App\DTOs\Auth\LoginUserDTO;
use Illuminate\Http\Request;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:student,client,university',
            'student_id' => 'required_if:role,student|string',
            'course' => 'required_if:role,student|string',
            'university' => 'required_if:role,student|string',
            'cpf' => 'required_if:role,client|string',
            'birth_date' => 'required_if:role,client|date',
            'university_name' => 'required_if:role,university|string',
            'cnpj' => 'required_if:role,university|string',
            'address' => 'required_if:role,university|string',
        ]);

        try {
            $dto = RegisterUserDTO::fromArray($request->all());
            $this->authService->registerPendingUser($dto);
            return response()->json(['message' => 'E-mail enviado! Confirme sua conta para ativar.'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function verifyEmail($token)
    {
        try {
            $result = $this->authService->confirmEmail($token);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $dto = new LoginUserDTO($request->email, $request->password);
            $result = $this->authService->login($dto);

            if (!$result) {
                return response()->json(['message' => 'Credenciais inválidas.'], 401);
            }

            //  Adiciona XP ao logar
            app(\App\Services\GamificationService::class)->handleDailyLogin($result['user']);

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $this->authService->logout($user);
            }
            return response()->json(['message' => 'Logout realizado com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->authService->resendVerificationEmail($request->email);
            return response()->json(['message' => 'E-mail de verificação reenviado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    
}