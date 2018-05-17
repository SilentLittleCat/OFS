<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\MoneyController;
use App\Models\BaseAttachmentModel;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderSend;
use App\Models\User;
use App\Models\Activity;
use App\Models\Address;
use App\Models\Food;
use App\Models\OrderShare;
use App\Models\OrderAfterSale;
use Auth, DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
    	$page = 'user';
    	$page_label = '我的订单';
        $title = '我的订单';
    	$back_url = route('users.index');

        $orders = Order::where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
        $wait_pays = $orders->where('pay_status', 0);
        $go_ons = $orders->where('pay_status', 1)->where('status', 0);
        $completes = $orders->where('status', 1);

    	return view('web.orders.index', compact('page', 'page_label', 'back_url', 'title', 'orders', 'wait_pays', 'go_ons', 'completes'));
    }

    public function show(Request $request, $order_id)
    {
    	$page = 'user';
    	$page_label = '订单详情';
        $title = '订单详情';
    	$back_url = route('orders.index');
        $hide_bottom = 0;

        $order = Order::find($order_id);
        if($order == null) {
            return back();
        }

        $send_info = Order::getSendInfo($order->id);

        $details = ($order->dates_details == null ? '' : $order->dates_details);
        $tmps = collect(explode(',', $details));
        $dates = collect();

        foreach($tmps as $tmp) {
            $item = explode(':', $tmp);
            if(count($item) > 1) {
                $dates->push($item[0]);
            }
        }
    	
    	return view('web.orders.show', compact('page', 'page_label', 'back_url', 'title', 'order', 'send_info', 'dates'));
    }

    public function send(Request $request)
    {
        $orders = OrderSend::where('user_id', Auth::user()->id)->orderBy('time')->get();
        $today = Carbon::now()->toDateString();
        $not_send_orders = $orders->where('status', 0);
        $sending_orders = $orders->where('status', 1);
        $sended_orders = $orders->where('status', 2);
        $back_money_orders = $orders->where('status', 4);

        $page = 'send';
        $page_label = '配送情况';
        $title = '配送情况';

        return view('web.orders.send', compact('page', 'page_label', 'title', 'not_send_orders', 'sending_orders', 'sended_orders', 'back_money_orders'));
    }

    public function afterSale(Request $request)
    {
        $orders = OrderAfterSale::where('user_id', Auth::user()->id)->get();

        $wait_look = $orders->where('status', 0);
        $back_money = $orders->where('status', 1);
        $not_pass = $orders->where('status', 2);

        $page = 'user';
        $page_label = '售后订单';
        $title = '售后订单';
        $back_url = route('users.contact');

        return view('web.orders.after-sale', compact('page', 'page_label', 'title', 'back_url', 'orders', 'wait_look', 'back_money', 'not_pass'));
    }

    public function showAfterSale(Request $request, $id)
    {
        $page = 'user';
        $page_label = '售后订单详情';
        $title = '售后订单详情';
        $back_url = route('orders.after.sale');
        $hide_bottom = 0;

        $order = OrderAfterSale::find($id);
        if($order == null) {
            return back();
        }

        // $send_info = Order::getSendInfo($order->id);

        // $details = ($order->dates_details == null ? '' : $order->dates_details);
        // $tmps = collect(explode(',', $details));
        // $dates = collect();

        // foreach($tmps as $tmp) {
        //     $item = explode(':', $tmp);
        //     if(count($item) > 1) {
        //         $dates->push($item[0]);
        //     }
        // }
        
        return view('web.orders.show-after-sale', compact('page', 'page_label', 'back_url', 'title', 'order'));
    }

    public function applyAfterSale(Request $request)
    {
        $page = 'user';
        $page_label = '申请售后';
        $title = '申请售后';
        $back_url = route('users.index');
        return view('web.orders.apply-after-sale', compact('page', 'page_label', 'back_url'));
    }

    public function ajaxAfterSale(Request $request)
    {
        $orders = OrderSend::where('time', $request->date)->orderBy('updated_at', 'desc')->get();
        // $orders = OrderSend::where('id', '<', 5)->get();
        foreach($orders as $order) {
            $order->order_id = sprintf('%013s', $order->order_id);
        }
        $orders->money = $orders->sum('price');
        return response()->json($orders, 200);
    }

    public function createAfterSale(Request $request)
    {
        if(! $request->has('order') || count($request->order) == 0) {
            return back()->withErrors(['表单有错！']);
        }
        foreach($request->order as $order) {
            $order_send = OrderSend::find($order);
            if($order_send != null) {
                OrderAfterSale::create([
                    'user_id' => $order_send->user_id,
                    'order_send_id' => $order_send->id,
                    'order_id' => $order_send->order_id,
                    'gender' => $order_send->gender,
                    'name' => $order_send->name,
                    'price' => $order_send->price,
                    'tel' => $order_send->tel,
                    'type' => $order_send->type,
                    'num' => $order_send->num,
                    'time' => $order_send->time,
                    'img' => $request->poster,
                    'reason' => $request->reason,
                    'address' => $order_send->address,
                    'status' => 0
                ]);
            }
        }

        return redirect()->route('orders.after.sale');
    }

    public function award()
    {
        $page = 'user';
        $page_label = '奖励订单';
        $title = '奖励订单';
        $back_url = route('users.recommend');
        $orders = Order::where([
            ['user_id', Auth::user()->id],
            ['is_prize', 1]
        ])->orderBy('updated_at', 'desc')->get();
        
        return view('web.orders.award', compact('page', 'page_label', 'back_url', 'title', 'orders'));
    } 

    public function showAward(Request $request, $order_id)
    {
        $page = 'user';
        $page_label = '奖励订单详情';
        $title = '奖励订单详情';
        $back_url = route('orders.award');
        
        return view('web.orders.show-award', compact('page', 'page_label', 'back_url', 'title'));
    }

    public function money()
    {
        $page = 'user';
        $page_label = '提现';
        $title = '提现';
        $back_url = route('users.recommend');
        
        return view('web.orders.money', compact('page', 'page_label', 'back_url', 'title'));
    }

    public function share(Request $request)
    {
        $order = null;
        if(!$request->has('id') || ($order = Order::find($request->id)) == null || $order->type != 3) {
            return back();
        }

        $master_order = Order::find(OrderShare::where('order_id', $request->id)->first()->master_order_id);
        $order_shares = OrderShare::where('master_order_id', $master_order->id)->get();
        $food_info = Order::getSendInfo($master_order->id);
        $page = 'user';
        $page_label = '分享页';
        $title = '分享页';
        $back_url = route('orders.index');
        $work_food_img = BaseAttachmentModel::where('class', '工作餐图')->first();
        $is_confirm = OrderShare::where([
            ['master_order_id', $master_order->id],
            ['user_id', Auth::user()->id]
        ])->get()->count() == 0 ? 0 : 1;
        
        return view('web.orders.share', compact('page', 'page_label', 'back_url', 'title', 'work_food_img', 'master_order', 'order_shares', 'food_info', 'order', 'is_confirm'));
    }

    public function shareCode(Request $request)
    {
        $order = null;
        if(!$request->has('id') || ($order = Order::find($request->id)) == null || $order->type != 3) {
            return back();
        }

        $page = 'user';
        $page_label = '分享二维码';
        $title = '分享二维码';
        $back_url = route('orders.index');

        return view('web.orders.share-code', compact('page', 'page_label', 'back_url', 'title', 'order'));
    }

    public function generate(Request $request)
    {
        $remain_money = Auth::user()->remain_money;
        $remain_times = 0;
        $origin_money = 0;
        $is_discount = 0;
        $is_use_coupon = 0;
        $discount_money = 0;
        $real_need_pay = 0;
        $single_price = 0;
        $coupon = null;
        $act = null;
        $total_num = 0;
        $need_pay_num = 0;
        $order_id = null;
        $order = null;
        $address = null;
        $recommend = $request->has('recommend') ? $request->recommend : null;
        $share = $request->has('share') ? (int)$request->share : 0;
        if($request->address == 'new-address') {
            $address = $request->full_address;
            Address::create([
                'user_id' => Auth::user()->id,
                'address' => $request->full_address,
                'username' => $request->real_name,
                'tel' => $request->tel
            ]);
        } else {
            $address = $request->address;
        }

        if($request->type == 1) {
            $single_price = (int)(Food::getFoodPrice(1));
            $remain_times = Auth::user()->man_remain_times;
        } else if($request->type == 2) {
            $single_price = (int)(Food::getFoodPrice(2));
            $remain_times = Auth::user()->woman_remain_times;
        } else if($request->type == 3) {
            $single_price = (int)(Food::getFoodPrice(3));
            $remain_times = Auth::user()->work_remain_times;
        }

        // 首页购买并配送
        if($request->method == 0) {
            $total_num = 0;
            if($request->food_set == 0) {
                $total_num = 5;
            } else if($request->food_set == 1) {
                $total_num = 20;
            } else if($request->food_set == 'custom') {
                if($request->dates_details == null) {
                    $total_num = 0;
                } else {
                    $tmp = explode(',', $request->dates_details);
                    $total_num = count($tmp);
                }
                $request->food_set = 2;
            }
            // $need_pay_num = ($remain_times >= $total_num ? 0 : $total_num - $remain_times);
            // $days = explode(',', $request->dates_details);
            $send_money = $total_num * Food::getSendMoney();
            $tmp = ($request->food_set_money == null ? 0 : $request->food_set_money);
            $origin_money = 0;
            if($request->food_set != 'custom') {
                $origin_money = $tmp + $send_money;
            }
            $user = Auth::user();
            $use_coupon = 0;

            $coupon = null;
            if($request->food_set != 'custom') {
                $coupon = User::find(Auth::user()->id)->coupons()->where([
                    ['coupon_user.status', 0],
                    ['condition', '<=', $origin_money],
                    ['type', 0]
                ])->orWhere([
                    ['coupon_user.status', 0],
                    ['condition', '<=', $origin_money],
                    ['type', $request->type]
                ])->orderBy('money', 'desc')->first();
                if($coupon != null) {
                    $is_discount = 1;
                    $is_use_coupon = 1;
                    $discount_money = $coupon->money;
                    $coupon->pivot->status = 1;
                    
                    $use_coupon = $coupon->id;
                }
            }

            $use_remain_times = 0;
            if($request->food_set != 'custom') {
                $use_remain_times = $total_num;
            }
            $remarks = ($request->info == null ? '' : $request->info . '<br>');
            if($use_remain_times != 0) {
                $remarks .= '使用了' . ($use_remain_times) . '次剩余次数；<br>';
            }
            if($is_discount == 1) {
                $remarks .= '使用了ID为' . $coupon->id . '的优惠券：' . $coupon->name . '<br>';
            }

            $need_pay_money = 0;
            if($request->food_set != 'custom') {
                $need_pay_money = round($origin_money - $discount_money, 2);
            }

            if($request->food_set != 'custom' && $user->remain_money < $need_pay_money) {
                $info = '实际应付：' . $need_pay_money . '元；账户余额：' . $user->remain_money . '元。请充值！'; 
                return back()->withErrors(['pay_error' => $info]);
            }

            $send_num = 0;
            $not_send_num = 0;
            if($request->food_set != 'custom') {
                $send_num = 0;
                if($request->dates_details != null) {
                    $tmp = explode(',', $request->dates_details);
                    $send_num = count($tmp);
                }

                $not_send_num = $total_num - $send_num;

                if($not_send_num > 0) {
                    if($request->type == 1) {
                        $user->man_remain_times += $not_send_num;
                    } else if($request->type == 2) {
                        $user->woman_remain_times += $not_send_num;
                    } else if($request->type == 3) {
                        $user->work_remain_times += $not_send_num;
                    }
                }
            } else {
                $send_num = 0;
                if($request->dates_details != null) {
                    $tmp = explode(',', $request->dates_details);
                    $send_num = count($tmp);
                }

                if($request->type == 1) {
                    $user->man_remain_times -= $send_num;
                } else if($request->type == 2) {
                    $user->woman_remain_times -= $send_num;
                } else if($request->type == 3) {
                    $user->work_remain_times -= $send_num;
                }
            }


            // $user->remain_money -= $need_pay_money;
            // $user->save();

            $order = Order::create([
                'user_id' => $user->id,
                'wechat_id' => $user->wechat_id,
                'wechat_name' => $user->wechat_name,
                'real_name' => $request->real_name,
                'gender' => $user->gender,
                'tel' => $request->tel,
                'money' => $need_pay_money,
                'price' => round($need_pay_money / $total_num, 2),
                'discount_money' => $discount_money,
                'address' => $address,
                'method' => 0,
                'type' => $request->type,
                'food_set' => $request->food_set,
                'num' => $total_num,
                'dates' => $request->dates,
                'dates_details' => $request->dates_details,
                'days' => $request->total_num,
                'pay_status' => 0,
                'status' => 0,
                'remarks' => $remarks,
                'use_remain_times' => $use_remain_times,
                'use_coupon' => $use_coupon,
                'send_money' => $send_money
            ]);

            if($coupon != null) {
                $coupon->save();
            }

            $user->save();

            // if($request->type == 3 && $use_remain_times == 0 && $use_coupon == 0) {
            //     OrderShare::create([
            //         'master_order_id' => $order->id,
            //         'order_id' => $order->id,
            //         'user_id' => $user->id,
            //         'wechat_id' => $user->wechat_id,
            //         'wechat_name' => $user->wechat_name,
            //         'real_name' => $user->real_name
            //     ]);
            // }

            // foreach($days as $day) {
            //     $items = explode(':', $day);
            //     OrderSend::create([
            //         'user_id' => $user->id,
            //         'order_id' => $order->id,
            //         'name' => $request->real_name,
            //         'tel' => $request->tel,
            //         'type' => $request->type,
            //         'num' => $items[1],
            //         'time' => $items[0],
            //         'address' => $address,
            //         'price' => round((double)($origin_money - $discount_money) / (double)$request->total_num, 2),
            //         'status' => 0
            //     ]);
            // }
            // if($request->type == 1) {
            //     $user->man_times += $total_num;
            //     $user->man_remain_times -= ($total_num - $need_pay_num);
            // } elseif($request->type == 2) {
            //     $user->woman_times += $total_num;
            //     $user->woman_remain_times -= ($total_num - $need_pay_num);
            // } elseif($request->type == 3) {
            //     $user->work_times += $total_num;
            //     $user->work_remain_times -= ($total_num - $need_pay_num);
            // }
            // $user->save();
        } elseif($request->method == 1) { //多次购买，暂不配送

            $user = Auth::user();
            $act = Activity::find($request->act_id);
            $total_num = (int)$act->times;
            $origin_money = $single_price * $total_num;
            $discount_money = round($act->money, 2);
            $remarks = '参加了活动：' . $act->name . '；优惠金额：' . $act->money . '；活动ID：' . $act->id;

            $need_pay_money = round($origin_money - $discount_money, 2);

            if($user->remain_money < $need_pay_money) {
                $info = '实际应付：' . $need_pay_money . '元；账户余额：' . $user->remain_money . '元。请充值！'; 
                return back()->withErrors(['pay_error' => $info]);
            }

            $user = Auth::user();

            $order = Order::create([
                'user_id' => $user->id,
                'wechat_id' => $user->wechat_id,
                'wechat_name' => $user->wechat_name,
                'real_name' => $user->real_name,
                'gender' => $user->gender,
                'tel' => $user->tel,
                'money' => $need_pay_money,
                'discount_money' => $discount_money,
                'method' => 1,
                'type' => $request->type,
                'num' => $total_num,
                'pay_status' => 0,
                'status' => 0,
                'remarks' => $remarks,
                'use_act' => $act->id
            ]);

            // if($request->type == 1) {
            //     $user->man_times += $total_num;
            //     $user->man_remain_times += $total_num;
            // } elseif($request->type == 2) {
            //     $user->woman_times += $total_num;
            //     $user->woman_remain_times += $total_num;
            // } elseif($request->type == 3) {
            //     $user->work_times += $total_num;
            //     $user->work_remain_times += $total_num;
            // }
            // $user->save();
        }

        // if($share == 1) {
            return redirect()->route('orders.index');
        // } else {
        //     return view('web.money.index', [
        //         'order' => $order,
        //         'remain_money' => $remain_money,
        //         'origin_money' => $origin_money,
        //         'real_need_pay' => $origin_money - $discount_money,
        //         'act' => $act,
        //         'coupon' => $coupon,
        //         'recommend' => $recommend,
        //         'total_num' => $total_num
        //     ]);
        // }

        // return redirect()->route('money.index', [
        //     'order' => $order,
        //     'remain_money' => $remain_money,
        //     'origin_money' => $origin_money,
        //     'real_need_pay' => $origin_money - $discount_money,
        //     'act' => $act,
        //     'coupon' => $coupon,
        //     'recommend' => $recommend
        // ]);
    }

    public function confirm(Request $request)
    {
        $order = null;
        if(!$request->has('id') || ($order = Order::find($request->id)) == null) {
            return back();
        }
        $user = Auth::user();
        $share_order = OrderShare::where([
            ['master_order_id', $order->id],
            ['user_id', $user->id]
        ])->first();
        if($share_order != null) {
            return back();
        }
        $master_order = Order::find(OrderShare::where('order_id', $order->id)->first()->master_order_id);

        if($user->remain_money < $master_order->money) {
            $info = '实际应付：' . $master_order->money . '元；账户余额：' . $user->remain_money . '元。请充值！'; 
            return back()->withErrors(['pay_error' => $info]);
        }

        $shares = OrderShare::where('master_order_id', $master_order->id)->get();
        $num = $shares->count();
        $price = Food::getFoodPrice(3, $num + 1);
        $send_money = $order->send_money;
        $money = $order->num * $price + $send_money;
        $ids = OrderShare::where('master_order_id', $master_order->id)->select('order_id')->get()->toArray();
        DB::table('orders')->whereIn('id', $ids)->update(['money' => $money]);

        $a_order = Order::create([
            'user_id' => $user->id,
            'wechat_id' => $user->wechat_id,
            'wechat_name' => $user->wechat_name,
            'real_name' => $user->real_name,
            'gender' => $user->gender,
            'tel' => $user->tel,
            'money' => $money,
            'address' => $order->address,
            'method' => 0,
            'type' => $order->type,
            'num' => $order->num,
            'dates' => $order->dates,
            'dates_details' => $order->dates_details,
            'days' => $order->days,
            'pay_status' => 0,
            'status' => 0,
            'remarks' => $order->remarks,
            'send_money' => $order->send_money
        ]);

        OrderShare::create([
           'master_order_id' => $order->id,
           'order_id' => $a_order->id,
           'user_id' => $user->id,
           'wechat_id' => $user->wechat_id,
           'wechat_name' => $user->wechat_name,
           'real_name' => $user->real_name
       ]);

        // $order_sends = OrderSend::where('order_id', $order->id)->get();
        // foreach($order_sends as $order_send) {
        //     OrderSend::create([
        //         'user_id' => $user->id,
        //         'order_id' => $a_order->id,
        //         'name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'type' => $a_order->type,
        //         'num' => $order_send->num,
        //         'time' => $order_send->time,
        //         'address' => $order_send->address,
        //         'price' => $order_send->price,
        //         'status' => 0
        //     ]);
        // }

        return redirect()->route('orders.show', ['order_id' => $a_order->id]);
    }

    public function changeTime(Request $request)
    {
        $send = null;
        if(!$request->has('id') || !$request->has('time') || ($send = OrderSend::find($request->id)) == null) {
            return back()->withErrors(['找不到要修改时间的订单！']);
        }

        $send->time = $request->time;
        if(! $send->saveOrFail()) {
            return back()->withErrors(['数据库储存出错！']);
        }

        return back();
    }

    public function changeAdd(Request $request)
    {
        $page = 'send';
        $page_label = '更改地址';
        $title = '更改地址';
        $back_url = route('orders.send');

        $send = null;
        if(! $request->has('id') || ($send = OrderSend::find($request->id)) == null) {
            return back()->withErrors(['找不到要修改时间的订单！']);
        }
        $addresses = Address::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('web.orders.change-add', compact('send', 'addresses', 'page', 'page_label', 'title', 'back_url'));
    }

    public function changeAddress(Request $request)
    {
        $send = null;
        if(!$request->has('id') || !$request->has('address') || ($send = OrderSend::find($request->id)) == null) {
            return back()->withErrors(['找不到要修改时间的订单！']);
        }
        $send->address = $request->address;
        if(! $send->saveOrFail()) {
            return back()->withErrors(['数据库储存出错！']);
        }

        return redirect()->route('orders.send');
    }
}
