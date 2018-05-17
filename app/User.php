<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wechat_id', 'wechat_name', 'real_name', 'password', 'gender', 'birthday', 'weight', 'height', 'tel', 'address', 'recommend', 'prize_money', 'total_pay', 'total_consume', 'score', 'man_times', 'woman_times', 'work_times', 'man_remain_times', 'woman_remain_times', 'work_remain_times', 'recommend_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
