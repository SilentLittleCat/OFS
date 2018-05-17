<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
	protected $table = 'scores';

    protected $fillable = [
        'user_id', 'coupon_id', 'status', 'name', 'score', 'order_id'
    ];

    public static function getRate()
    {
    	return 1;
    }
}
