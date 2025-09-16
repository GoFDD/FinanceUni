<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // student, cliente, university, admin
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamentos com tabelas extras
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function university()
    {
        return $this->hasOne(University::class);
    }
}