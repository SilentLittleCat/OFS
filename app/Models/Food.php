<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Food extends Model
{
    protected $fillable = [
        'name', 'money', 'type', 'a_min', 'a_max', 'a_price', 'b_min', 'b_price', 'max_people', 'poster', 'info', 'status'
    ];

    public static function getFoodPrice($type, $num = 1)
    {
    	$price = 40;
    	$type = (int)$type;
    	$num = (int)$num;
    	$food = static::where('type', $type)->first();
    	if($food == null) {
    		return $price;
    	}
    	if($type == 3) {
    		if($num >= $food->b_min) {
    			$price = $food->b_price;
    		} elseif($num >= $food->a_min && $num <= $food->a_max) {
    			$price = $food->a_price;
    		} else {
    			$price = $food->money;
    		}
    	} else {
    		$price = $food->money;
    	}
    	return $price;
    }

    public static function getSendMoney()
    {
        $money = DB::table('base_dictionary_option')->where([
            ['dictionary_table_code', 'send'],
            ['dictionary_code', 'money'],
            ['key', 'money']
        ])->first();
        // if($money == null) {
        //     DB::table('base_dictionary_option')->insert([
        //         ['dictionary_table_code', 'send'],
        //         ['dictionary_code', 'money'],
        //         ['key', 'money']
        //     ]);
        // }
        return $money == null ? 3 : $money->value;
    }
}
