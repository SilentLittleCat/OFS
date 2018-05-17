<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;
use App\Models\OrderSend;

class Order extends Model
{
    protected $guarded = [];

    public static function getSendInfo($id)
    {
    	$order = static::find($id);
    	$info = '';
        if($order->dates_details != null) {
            foreach(explode(',', $order->dates_details) as $item) {
                $tmp = explode(':', $item);
                $tmp2 = explode('-', $tmp[0]);
                $date = Carbon::createFromDate($tmp2[0], $tmp2[1], $tmp2[2]);
                $info .= $date->month . '月' . $date->day . '日：' . $tmp[1] . '份<br>';
            }
        }
    	return $info;
    }

    public static function generateSend($id)
    {
        $order = static::find($id);
        if($order->method == 0) {
            $user = User::find($order->user_id);
            if($order->dates_details != null) {
                $days = explode(',', $order->dates_details);
                foreach($days as $day) {
                    if(!empty($day)){
                        $items = explode(':', $day);
                        OrderSend::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'name' => $order->real_name,
                            'gender' => $user->gender,
                            'tel' => $order->tel,
                            'type' => $order->type,
                            'num' => $items[1],
                            'time' => $items[0],
                            'address' => $order->address,
                            'price' => $order->price,
                            'status' => 0
                        ]);
                    }
                }
            }
        }
    }
}
