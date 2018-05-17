<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendRange extends Model
{
    protected $table = 'send_ranges';

    protected $fillable = [
        'city', 'county', 'status', 'created_at', 'updated_at'
    ];
}
