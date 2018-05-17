<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name', 'type', 'condition', 'money', 'status', 'total', 'use_total', 'description', 'begin_time', 'end_time'
    ];
}
