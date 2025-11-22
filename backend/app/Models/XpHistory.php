<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class XpHistory extends Model
{
    use HasFactory;

    protected $table = 'xp_history';

    protected $fillable = [
        'user_id',
        'source',         // origem do XP: "meta", "conquista", "bônus diário", etc.
        'amount',         // quantidade de XP
        'description',    // texto explicando a origem
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
