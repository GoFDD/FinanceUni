<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PluggyItem extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'institution',
        'status',
        'connector_id',
        'last_sync',
    ];

    public function accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
