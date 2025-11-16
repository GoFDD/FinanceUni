<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'pluggy_item_id',
        'pluggy_account_id',
        'name',
        'type',
        'subtype',
        'number',
        'balance',
        'currency_code',
        'institution_name',
    ];
}
