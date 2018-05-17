<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    protected $fillable = [
        'name', 'sort', 'status'
    ];
}
