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
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
            'course' => $dto->course,
            'student_id' => $dto->student_id,
            'university' => $dto->university,
        ]);

        return $user;
    }

    public function login(LoginUserDTO $dto): string|bool
    {
        $user = User::where('email', $dto->email)->first();
        if (!$user || !Hash::check($dto->password, $user->password)) {
            return false;
        }

        //  token 
        return $user->createToken('auth_token')->plainTextToken;
    }
}
