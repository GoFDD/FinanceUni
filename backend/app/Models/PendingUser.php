<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PendingUser extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'role', 'extra_data', 'verification_token', 'expire_at'];

    protected $casts = [
        'extra_data' => 'array',
        'expire_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->verification_token = Str::uuid();
            $user->expire_at = now()->addHours(12);
        });
    }
}

