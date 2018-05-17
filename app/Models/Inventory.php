<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'scene', 'two_level', 'three_level', 'is_in', 'type', 'is_direct_cost', 'name', 'username', 'standard', 'unit', 'num', 'price', 'total_money', 'old_years', 'old_rate', 'old_cost', 'remarks', 'last_enter_time', 'last_out_time', 'status'
    ];
}
