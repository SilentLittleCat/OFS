<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendChange extends Model
{
	protected $table = 'send_changes';

    protected $fillable = [
        'order_id', 'send_id', 'user_id', 'username', 'type', 'info', 'food_type', 'back_money'
    ];
}
