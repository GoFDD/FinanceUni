<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário de teste Julio
        User::create([
            'name' => 'Julio Teste',
            'email' => 'julio.teste@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'university',
            'course' => 'Análise e Desenvolvimento de Sistemas',
            'student_id' => '20250101',
            'university' => 'Universidade X',
        ]);

        // Usuário comum adicional
        User::create([
            'name' => 'Maria Universitária',
            'email' => 'maria@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
    }
}
