<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostRange extends Model
{
    protected $table = 'cost_range';

    protected $fillable = [
        'name', 'range_min', 'range_max', 'info', 'status', 'color'
    ];
}
