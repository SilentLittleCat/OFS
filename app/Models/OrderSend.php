<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSend extends Model
{
    protected $table = 'order_send';

    protected $fillable = [
        'user_id', 'order_id', 'name', 'tel', 'type', 'num', 'time', 'address', 'status', 'remarks', 'price', 'order_send'
    ];
}
