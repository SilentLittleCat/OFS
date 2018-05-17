<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $table = 'costs';

    protected $fillable = [
        'scene', 'type', 'time', 'money', 'add_date', 'username', 'remarks'
    ];
}