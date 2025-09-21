<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\DTOs\Auth\RegisterUserDTO;
use App\DTOs\Auth\LoginUserDTO;
use Illuminate\Http\Request;
use App\Models\PendingUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Registro de usuário pendente
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => 'required|in:student,client,university',
        ]);

        $extraFields = match ($request->role) {
            'student' => [
                'student_id' => $request->student_id,
                'course'     => $request->course,
                'university' => $request->university,
            ],
            'client' => [
                'cpf'        => $request->cpf,
                'birth_date' => $request->birth_date,
            ],
            'university' => [
                'university_name' => $request->university_name,
                'cnpj'            => $request->cnpj,
                'address'         => $request->address,
            ],
            default => []
        };

        // Cria DTO e registra pending user via service
        $dto = new RegisterUserDTO(
            $request->name,
            $request->email,
            $request->password,
            $request->role,
            $extraFields['student_id'],
            $extraFields['course'] ,
            $extraFields['university'],
            $extraFields['cpf'],
            $extraFields['birth_date'],
            $extraFields['university_name'],
            $extraFields['cnpj'],
            $extraFields['address']
        );

        $this->authService->registerPendingUser($dto);

        return response()->json(['message' => 'E-mail enviado! Confirme sua conta para ativar.']);
    }

    /**
     * Confirmação de e-mail
     */
    public function verifyEmail($token)
    {
        try {
            $result = $this->authService->confirmEmail($token);

            // Retorna token e user para frontend redirecionar para login
            return response()->json([
                'message' => 'E-mail confirmado! Redirecionando para login...',
                'token'   => $result['token'],
                'user'    => $result['user'],
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $dto = new LoginUserDTO(
            $request->email,
            $request->password
        );


        $result = $this->authService->login($dto);

        if (!$result) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        return response()->json($result);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $this->authService->logout($user);
        }

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }
}
