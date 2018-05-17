<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'name', 'level', 'pid', 'full_name', 'created_at', 'updated_at'
    ];
}
