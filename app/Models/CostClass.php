<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostClass extends Model
{
    protected $table = 'cost_class';

    protected $fillable = [
        'name', 'sort', 'status'
    ];
}
