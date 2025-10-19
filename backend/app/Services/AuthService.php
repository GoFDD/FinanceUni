<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Client;
use App\Models\University;
use App\Models\PendingUser;
use App\DTOs\Auth\RegisterUserDTO;
use App\DTOs\Auth\LoginUserDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmEmail;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function registerPendingUser(RegisterUserDTO $dto): PendingUser
    {
        if (PendingUser::where('email', $dto->email)->exists() || User::where('email', $dto->email)->exists()) {
            throw new \Exception('E-mail já cadastrado ou pendente.');
        }

        $extraData = array_filter([
            'student_id' => $dto->student_id,
            'course' => $dto->course,
            'university' => $dto->university,
            'cpf' => $dto->cpf,
            'birth_date' => $dto->birth_date,
            'university_name' => $dto->university_name,
            'cnpj' => $dto->cnpj,
            'address' => $dto->address,
        ]);

        $pendingUser = PendingUser::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
            'extra_data' => $extraData,
            'verification_token' => Str::uuid(),
            'expires_at' => now()->addHours(12),
            'email_confirmed' => false,
        ]);

        try {
            Mail::to($pendingUser->email)->send(new ConfirmEmail($pendingUser));
        } catch (\Exception $e) {
            throw new \Exception('Erro ao enviar e-mail de verificação.');
        }

        return $pendingUser;
    }

    public function confirmEmail(string $token): array
    {
        $pending = PendingUser::where('verification_token', $token)
                            ->where('expires_at', '>=', now())
                            ->first();

        if (!$pending) {
            throw new \Exception('Token inválido ou expirado.');
        }

        if ($pending->email_confirmed) {
            throw new \Exception('E-mail já confirmado.');
        }

        return DB::transaction(function () use ($pending) {
            $pending->email_confirmed = true;
            $pending->save();

            $dto = new RegisterUserDTO(
                $pending->name,
                $pending->email,
                $pending->password,
                $pending->role,
                $pending->extra_data['student_id'] ?? null,
                $pending->extra_data['course'] ?? null,
                $pending->extra_data['university'] ?? null,
                $pending->extra_data['cpf'] ?? null,
                $pending->extra_data['birth_date'] ?? null,
                $pending->extra_data['university_name'] ?? null,
                $pending->extra_data['cnpj'] ?? null,
                $pending->extra_data['address'] ?? null
            );

            $user = $this->register($dto);
            $pending->delete();

            return [
                'message' => 'E-mail confirmado com sucesso! Faça login para continuar.',
                'user' => $user
            ];
        });
    }

    public function register(RegisterUserDTO $dto): User
    {
        if (User::where('email', $dto->email)->exists()) {
            throw new \Exception('E-mail já existe.');
        }

        $password = $dto->password;
        if (!Hash::info($password)['algo']) {
            $password = Hash::make($dto->password);
        }

        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $password,
            'role' => $dto->role,
            'email_verified_at' => now(),
        ]);

        if ($dto->role === 'student' && $dto->student_id && $dto->course && $dto->university) {
            Student::create([
                'user_id' => $user->id,
                'student_id' => $dto->student_id,
                'course' => $dto->course,
                'university' => $dto->university,
            ]);
        }

        if ($dto->role === 'client' && $dto->cpf && $dto->birth_date) {
            Client::create([
                'user_id' => $user->id,
                'cpf' => $dto->cpf,
                'birth_date' => $dto->birth_date,
            ]);
        }

        if ($dto->role === 'university' && $dto->university_name && $dto->cnpj && $dto->address) {
            University::create([
                'user_id' => $user->id,
                'university_name' => $dto->university_name,
                'cnpj' => $dto->cnpj,
                'address' => $dto->address,
            ]);
        }

        return $user;
    }

    public function login(LoginUserDTO $dto): ?array
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user) {
            return null;
        }

        if (!$user->email_verified_at) {
            throw new \Exception('E-mail não confirmado.');
        }

        if (!Hash::check($dto->password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function resendVerificationEmail(string $email): void
    {
        $pendingUser = PendingUser::where('email', $email)->first();

        if (!$pendingUser) {
            throw new \Exception('Nenhum registro pendente encontrado para este e-mail.');
        }

        if ($pendingUser->email_confirmed) {
            throw new \Exception('E-mail já foi confirmado.');
        }

        if ($pendingUser->expires_at < now()) {
            $pendingUser->verification_token = Str::uuid();
            $pendingUser->expires_at = now()->addHours(12);
            $pendingUser->save();
        }

        try {
            Mail::to($pendingUser->email)->send(new ConfirmEmail($pendingUser));
        } catch (\Exception $e) {
            throw new \Exception('Erro ao reenviar e-mail de verificação.');
        }
    }
}