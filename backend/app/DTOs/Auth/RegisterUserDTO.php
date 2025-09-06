<?php

namespace App\DTOs\Auth;

class RegisterUserDTO
{
    public string $name;
    public string $email;
    public string $password;
    public ?string $role;
    public ?string $course;
    public ?string $student_id;
    public ?string $university;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->role = $data['role'] ?? 'user';
        $this->course = $data['course'] ?? null;
        $this->student_id = $data['student_id'] ?? null;
        $this->university = $data['university'] ?? null;
    }
}
