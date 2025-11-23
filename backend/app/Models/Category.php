<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'icon',
        'color',
    ];

    public function scopeForUserAndType($query, $userId, $type)
    {
        return $query->where('type', $type)
            ->where(function($q) use ($userId) {
                $q->whereNull('user_id')
                ->orWhere('user_id', $userId);
            });
    }

    // Categorias do sistema (user_id == null)
    public function scopeSystem($query)
    {
        return $query->whereNull('user_id');
    }

    // Categorias do usuário
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        });
    }

    // Relação com transactions 
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
