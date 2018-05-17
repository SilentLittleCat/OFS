<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = [
        'score', 'name', 'poster', 'info', 'status', 'coupon_id'
    ];
}
