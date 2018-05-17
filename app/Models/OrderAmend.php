<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAmend extends Model
{
    protected $table = 'order_amend';

    protected $fillable = [
        'user_id', 'amend_user_id', 'amend_user_name', 'type', 'origin_times', 'amend_times', 'reason'
    ];
}
