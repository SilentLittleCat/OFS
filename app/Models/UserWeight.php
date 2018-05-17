<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWeight extends Model
{
    protected $table = 'user_weight';

    protected $fillable = [
        'user_id', 'username', 'weight', 'created_at', 'updated_at'
    ];
}
