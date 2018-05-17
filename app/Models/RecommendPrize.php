<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendPrize extends Model
{
    protected $table = 'recommend_prize';

    protected $fillable = [
        'name', 'condition', 'back_money', 'info', 'status'
    ];
}
