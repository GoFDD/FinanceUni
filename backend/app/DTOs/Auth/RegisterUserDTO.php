<?php

namespace App\DTOs\Auth;

class RegisterUserDTO
{
    public string $name;
    public string $email;
    public string $password;
    public ?string $role;

    // Student
    public ?string $course;
    public ?string $student_id;
    public ?string $university;

    // Client
    public ?string $cpf;
    public ?string $birth_date;

    // University
    public ?string $university_name;
    public ?string $cnpj;
    public ?string $address;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->role = $data['role'] ?? 'user';

        $this->course = $data['course'] ?? null;
        $this->student_id = $data['student_id'] ?? null;
        $this->university = $data['university'] ?? null;

        $this->cpf = $data['cpf'] ?? null;
        $this->birth_date = $data['birth_date'] ?? null;

        $this->university_name = $data['university_name'] ?? null;
        $this->cnpj = $data['cnpj'] ?? null;
        $this->address = $data['address'] ?? null;
    }
}
