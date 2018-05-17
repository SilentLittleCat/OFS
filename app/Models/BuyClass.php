<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyClass extends Model
{
    protected $table = 'buy_class';

    protected $fillable = [
        'name', 'fa_class', 'sort', 'is_direct_cost', 'status'
    ];
}
