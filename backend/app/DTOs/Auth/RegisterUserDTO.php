<?php

namespace App\DTOs\Auth;

class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role,
        public ?string $student_id = null,
        public ?string $course = null,
        public ?string $university = null,
        public ?string $cpf = null,
        public ?string $birth_date = null,
        public ?string $university_name = null,
        public ?string $cnpj = null,
        public ?string $address = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['student_id'] ?? null,
            $data['course'] ?? null,
            $data['university'] ?? null,
            $data['cpf'] ?? null,
            $data['birth_date'] ?? null,
            $data['university_name'] ?? null,
            $data['cnpj'] ?? null,
            $data['address'] ?? null,
        );
    }
}
