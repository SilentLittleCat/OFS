<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarnLine extends Model
{
    protected $table = 'warn_line';

    protected $fillable = [
        'name', 'line', 'status', 'info', 'color'
    ];
}
