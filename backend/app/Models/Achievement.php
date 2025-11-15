<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',           // nome da conquista (ex: "Primeira meta concluída")
        'description',     // explicação do que foi alcançado
        'icon',            // ícone (pode ser o nome do ícone Lucide ou URL)
        'xp_reward',       // XP recebido
        'unlocked_at',     // data em que foi desbloqueada
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
