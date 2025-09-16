<?php

namespace App\Services;

use App\DTOs\Auth\RegisterUserDTO;
use App\DTOs\Auth\LoginUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(RegisterUserDTO $dto): User
    {
        // Cria o usuário principal
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);

        // Cria registros extras dependendo do role
        switch ($dto->role) {
            case 'student':
                $user->student()->create([
                    'student_id' => $dto->student_id,
                    'course' => $dto->course,
                    'university' => $dto->university,
                ]);
                break;

            case 'cliente':
                $user->client()->create([
                    'cpf' => $dto->cpf,
                    'birth_date' => $dto->birth_date,
                ]);
                break;

            case 'university':
                $user->university()->create([
                    'name' => $dto->university_name,
                    'cnpj' => $dto->cnpj,
                    'address' => $dto->address,
                ]);
                break;
        }

        return $user;
    }

    public function login(LoginUserDTO $dto): string|bool
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            return false;
        }

        // Retorna token de autenticação
        return $user->createToken('auth_token')->plainTextToken;
    }
}
