<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PendingUser extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'role', 'extra_data', 'verification_token', 'expires_at'];

    protected $casts = [
        'extra_data' => 'array',
        'expires_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->verification_token = Str::uuid();
            $user->expires_at = now()->addHours(12);
        });
    }
}

