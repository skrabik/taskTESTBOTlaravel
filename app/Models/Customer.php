<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_name',
        'telegram_user_id',
        'count_of_clicks',
        'status',
    ];
}
