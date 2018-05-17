<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendDate extends Model
{
    protected $table = 'send_dates';

    protected $fillable = [
        'date', 'status', 'created_at', 'updated_at'
    ];
}
