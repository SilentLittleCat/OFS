<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShare extends Model
{
    protected $table = 'order_share';

    protected $fillable = [
        'master_order_id', 'order_id', 'user_id', 'wechat_name', 'wechat_id', 'real_name'
    ];
}
