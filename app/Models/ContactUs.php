<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
	protected $table = 'contact_uses';
    protected $fillable = [
        'key', 'val'
    ];

    public static function getTel()
    {
    	return ContactUs::where('key', 'tel')->first();
    }

    public static function getTwoCode()
    {
    	return ContactUs::where('key', 'two_code')->first();
    }
}
