<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayMoney extends Model
{
    protected $table = 'pay_money';

    protected $fillable = [
        'money', 'status', 'user_id', 'username', 'created_at', 'updated_at'
    ];
}
