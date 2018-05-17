<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemainMoney extends Model
{
    protected $table = 'remain_moneys';

    protected $fillable = [
        'user_id', 'username', 'money', 'info'
    ];
}
