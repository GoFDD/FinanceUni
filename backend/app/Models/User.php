<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // student, client, university
        'email_verified_at',
        'xp',
        'level',
        'streak',
        'best_streak',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
    ];

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
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function xpHistory()
    {
        return $this->hasMany(XpHistory::class);
    }
}