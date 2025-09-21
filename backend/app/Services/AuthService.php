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

class AuthService
{
    /**
     * Registra um usuário pendente e envia e-mail de verificação
     */
    public function registerPendingUser(RegisterUserDTO $dto): PendingUser
    {
        // Evitar duplicidade
        if (PendingUser::where('email', $dto->email)->exists() || User::where('email', $dto->email)->exists()) {
            throw new \Exception('E-mail já cadastrado ou pendente.');
        }

        $extraData = [
            'student_id' => $dto->student_id,
            'course' => $dto->course,
            'university' => $dto->university,
            'cpf' => $dto->cpf,
            'birth_date' => $dto->birth_date,
            'university_name' => $dto->university_name,
            'cnpj' => $dto->cnpj,
            'address' => $dto->address,
        ];

        $pendingUser = PendingUser::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
            'extra_data' => $extraData,
            'verification_token' => Str::uuid(),
            'expire_at' => now()->addHours(12), // expira em 12h
        ]);

        // Envia e-mail de confirmação
        Mail::to($pendingUser->email)->send(new ConfirmEmail($pendingUser));

        return $pendingUser;
    }

    /**
     * Confirma e-mail, cria usuário final e registros extras
     */
    public function confirmEmail(string $token): User
    {
        $pending = PendingUser::where('verification_token', $token)
                              ->where('expire_at', '>=', now())
                              ->firstOrFail();

        if ($pending->email_confirmed) {
            throw new \Exception('E-mail já confirmado.');
        }

        // Marca como confirmado
        $pending->email_confirmed = true;
        $pending->save();

        // Cria usuário final
        $dto = new RegisterUserDTO(
            $pending->name,
            $pending->email,
            $pending->password, // já hash
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

        // Remove o registro pendente
        $pending->delete();

        return $user;
    }

    /**
     * Cria usuário final na tabela users
     */
    public function register(RegisterUserDTO $dto): User
    {
        // não aplicar Hash novamente
        $password = $dto->password;
        if (!Hash::info($password)['algo']) {
            $password = Hash::make($password);
        }

        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $password,
            'role' => $dto->role,
        ]);

        //registro na tabela extra de acordo com role
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

/**
 * Login e geração de token
 */
public function login(LoginUserDTO $dto): ?array
{
    $user = User::where('email', $dto->email)->first();

    if (!$user || !Hash::check($dto->password, $user->password)) {
        return null;
    }

    // Gerar token Sanctum
    $token = $user->createToken('auth_token')->plainTextToken;

    return [
        'token' => $token,
        'user' => $user,
    ];
}

    /**
     * Logout e revogação de token
     */
    public function logout(User $user): void
    {
        // Revoga todos os tokens do usuário
        $user->tokens()->delete();
    }
}
