<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackMoney extends Model
{
    protected $table = 'back_money';

    protected $fillable = [
        'money', 'status', 'method', 'username', 'tel', 'img', 'user_id'
    ];
}
