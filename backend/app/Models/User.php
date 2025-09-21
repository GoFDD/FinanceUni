<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // student, client, university, admin
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts automáticos
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // bcrypt automaticamente
    ];

    // Relacionamentos
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

    /**
     * Envia notificação de verificação de e-mail
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail()); 
    }
}
