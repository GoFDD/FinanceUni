<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_system_goal',
        'system_key',
        'title',
        'category',
        'target_value',
        'current_value',
        'xp_reward',
        'grants_achievement',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_system_goal' => 'boolean',
        'grants_achievement' => 'boolean',
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    //  Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //  Escopos para facilitar consultas
    public function scopeSystemGoals($query)
    {
        return $query->where('is_system_goal', true);
    }

    public function scopeUserGoals($query, $userId)
    {
        return $query->where('is_system_goal', false)
                     ->where('user_id', $userId);
    }

    //  Calcular progresso (%)
    public function getProgressAttribute(): float
    {
        if ($this->target_value <= 0) {
            return 0;
        }

        return min(100, ($this->current_value / $this->target_value) * 100);
    }
}
