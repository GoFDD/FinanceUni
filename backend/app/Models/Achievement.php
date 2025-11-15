<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'xp_reward',
        'rarity',
        'status',
    ];

    // RelacionamentoMany-to-Many com User
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('progress', 'unlocked_at')
            ->withTimestamps();
    }
}