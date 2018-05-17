<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'wechat_id', 'wechat_name', 'real_name', 'password', 'gender', 'birthday', 'weight', 'height', 'tel', 'address', 'recommend', 'prize_money', 'total_pay', 'total_consume', 'score', 'man_times', 'woman_times', 'work_times', 'man_remain_times', 'woman_remain_times', 'work_remain_times', 'recommend_by'
    ];

    public function coupons()
    {
    	return $this->belongsToMany('App\Models\Coupon')->withTimestamps()->withPivot('id', 'status');
    }
}
