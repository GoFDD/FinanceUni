<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitindo que qualquer usuário faça registro
    }

    public function rules(): array
    {
        $roles = ['student', 'client', 'university'];

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // confirmed = password_confirmation
            'role' => ['required', Rule::in($roles)],

            // Campos opcionais dependendo da role
            'student_id' => 'nullable|string|max:50',
            'course' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',

            'cpf' => 'nullable|string|max:14',
            'birth_date' => 'nullable|date',

            'university_name' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ];
    }
}
