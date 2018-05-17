<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name', 'type', 'money', 'times', 'status', 'description', 'begin_time', 'end_time'
    ];
}
