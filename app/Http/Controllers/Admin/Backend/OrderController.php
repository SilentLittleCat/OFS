<?php
namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Food;
use App\Models\Score;
use App\Models\OrderSend;
use App\Models\SendChange;
use App\Models\SendRange;
use App\Models\Address;
use App\Models\RecommendPrize;
use App\Models\RemainMoney;
use DB, Exception, Auth, Excel;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orders = Order::where('id', '>', 0);

        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {

            if($request->has('type') && $request->type != 0) {
                $orders->where('type', $request->type);
            }
            if($request->has('begin_time') && $request->begin_time != null) {
                $orders = $orders->where('created_at', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $orders = $orders->where('created_at', '<=', $request->end_time);
            }
            if($request->has('keyword') && $request->keyword != null && trim($request->keyword) != '') {
                $key = trim($request->keyword);
                if(((int)$key) != 0 && strlen($key) == 13) {
                    $orders = $orders->where('id', (int)$key);
                } elseif(((int)$key) != 0) {
                    $orders = $orders->where('tel', $key);
                } else {
                    $orders = $orders->where('real_name', $key);
                }
            }
        }

        if($request->has('sort_field')) {
            $orders = $orders->orderBy($request->sort_field, $request->sort_field_by);
        } else {
            $orders = $orders->orderBy('id', 'desc');
        }

        $orders = $orders->paginate(env('EACH_PAGE_NUM'));

        $info = $request->all();

        return view('admin.backend.orders.index', compact('orders', 'info'));
    }

    public function show(Request $request)
    {
        $order = null;
        if(! $request->has('order_id') || ($order = Order::find($request->order_id)) == null) {
            return back()->withErrors(['订单不存在！']);
        }

        return view('admin.backend.orders.show', compact('order'));
    }

    public function sendShow(Request $request)
    {
        $order = null;
        
        if(! $request->has('order_id') || ($order = Order::find($request->input('order_id'))) == null) {
            return back()->withErrors('该订单不存在！');
        }

        $user = User::find($order->user_id);
        $addresses = DB::table('addresses')->where('user_id', $user->id)->get();
        $records = DB::table('order_send')->where('order_id', $order->id);

        $records = $records->orderBy('time', 'desc')->paginate(env('EACH_PAGE_NUM'));

        foreach($records as $record) {
            // $record->back_money = floor(((double)$order->money - (double)$order->send_money) / (double)$order->num * (double)$record->num);
            if($order->num != 0) {
                $record->back_money = round((double)$order->money / (double)$order->num * (double)$record->num, 2);
            } else {
                $record->back_money = $order->money;
            }
            
        }

        $not_send_num = OrderSend::where([
            ['order_id', $order->id],
            ['status', 0]
        ])->get()->sum('num');
        // $order->back_money = floor(((double)$order->money - (double)$order->send_money) / (double)$order->num * (double)$not_send_num);
        $order->back_money = round((double)$order->money, 2);

        $info = $request->all();
        $man_food_price = Food::getFoodPrice(1);
        $woman_food_price = Food::getFoodPrice(2);
        $work_food_price = Food::getFoodPrice(3);
        $ranges = SendRange::all();

        return view('admin.backend.orders.send-show', compact('user', 'records', 'info', 'order', 'addresses', 'man_food_price', 'woman_food_price', 'work_food_price', 'ranges'));
    }

    public function amendRecord(Request $request)
    {
    	$user = null;
    	
    	if(! $request->has('id') || ($user = User::find($request->input('id'))) == null) {
    		return back()->withErrors('该用户不存在！');
    	}

    	$records = DB::table('order_amend')->where('user_id', $user->id);

    	if($request->method() == 'POST' && $request->has('search') && $request->search == true) {

    		if($request->has('type') && $request->type != 0) {
    			$records = $records->where('type', $request->type);
    		}
			if($request->has('begin_time') && $request->begin_time != null) {
				$records = $records->where('created_at', '>', $request->begin_time);
			}
			if($request->has('end_time') && $request->end_time != null) {
				$records = $records->where('created_at', '<', $request->end_time);
			}
    	}

        if($request->has('sort_field')) {
            $users = $users->orderBy($request->sort_field, $request->sort_field_by);
        }

    	$records = $records->orderBy('created_at', 'desc')->paginate(env('EACH_PAGE_NUM'));

    	$info = $request->all();

    	return view('admin.backend.orders.amend-record', compact('user', 'records', 'info'));
    }

    public function sendRecord(Request $request)
    {
        $order = null;
        
        if(! $request->has('order_id') || ($order = Order::find($request->input('order_id'))) == null) {
            return back()->withErrors('该订单不存在！');
        }

        $user = User::find($order->user_id);
        $records = DB::table('order_send')->where('order_id', $order->id);

        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {

            if($request->has('type') && $request->type != 0) {
                $records = $records->where('type', $request->type);
            }
            if($request->has('begin_time') && $request->begin_time != null) {
                $records = $records->where('time', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $records = $records->where('time', '<=', $request->end_time);
            }
        }

        if($request->has('sort_field')) {
            $records = $records->orderBy($request->sort_field, $request->sort_field_by);
        }

        $records = $records->paginate(env('EACH_PAGE_NUM'));

        $info = $request->all();

        return view('admin.backend.orders.send-record', compact('user', 'records', 'info', 'order'));
    }

    public function userSendRecord(Request $request)
    {
        $user = null;
        
        if(! $request->has('id') || ($user = User::find($request->input('id'))) == null) {
            return back()->withErrors('该用户不存在！');
        }

        $records = DB::table('order_send')->where('user_id', $user->id);

        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {
            if($request->has('type') && $request->type != 0) {
                $records = $records->where('type', $request->type);
            }
            if($request->has('begin_time') && $request->begin_time != null) {
                $records = $records->where('time', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $records = $records->where('time', '<=', $request->end_time);
            }
            if($request->has('order_id') && $request->order_id != null) {
                $records = $records->where('order_id', '=', (int)$request->order_id);
            }
        }

        $records = $records->orderBy('time', 'desc')->paginate(env('EACH_PAGE_NUM'));

        $info = $request->all();

        return view('admin.backend.orders.user-send-record', compact('user', 'records', 'info'));
    }

    public function userIndex(Request $request)
    {
        $user = null;
        if(! $request->has('id') || ($user = User::find($request->id)) == null) {
            return back()->withErrors(['该用户不存在！']);
        }

        $orders = Order::where('user_id', $user->id);

        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {

            if($request->has('type') && $request->type != 0) {
                $orders->where('type', $request->type);
            }
            if($request->has('begin_time') && $request->begin_time != null) {
                $orders = $orders->where('created_at', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $orders = $orders->where('created_at', '<=', $request->end_time);
            }
        }

        if($request->has('sort_field')) {
            $orders = $orders->orderBy($request->sort_field, $request->sort_field_by);
        }

        $orders = $orders->paginate(env('EACH_PAGE_NUM'));

        $info = $request->all();

        return view('admin.backend.orders.user-index', compact('user', 'orders', 'info'));
    }

    public function changeSendAddress(Request $request)
    {
        $record = null;
        $address = null;
        if($request->method() != 'POST' || !$request->has('record_id') || ($record = DB::table('order_send')->where('id', $request->record_id)->first()) == null) {
            return back()->withErrors(['找不到配送记录！或者数据库出错！']);
        }

        if(((int)$request->use_new_address) == 0) {
            $address = DB::table('addresses')->where('id', $request->select_address)->first()->address;
        } else {
            $address = $request->detail_address;
            Address::create([
                'user_id' => $record->user_id,
                'address' => $request->detail_address
            ]);
        }

        $info = '原来地址：' . $record->address . '；更改后地址：' . $address;

        SendChange::create([
            'order_id' => $record->order_id,
            'send_id' => $record->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => 2,
            'info' => $info
        ]);

        try {
             DB::transaction(function() use ($request, $address) {
                // throw new Exception("Connect failed!");

                DB::table('order_send')->where('id', $request->record_id)->update([
                    'address' => $address
                ]);
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function delaySendTime(Request $request)
    {
        $record = null;
        if($request->method() != 'POST' || !$request->has('record_id') || ($record = DB::table('order_send')->where('id', $request->record_id)->first()) == null) {
            return back()->withErrors(['找不到配送记录！或者数据库出错！']);
        }

        $info = '原配送时间：' . $record->time . '；更改后配送时间：' . $request->changed_time;

        SendChange::create([
            'order_id' => $record->order_id,
            'send_id' => $record->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => 3,
            'info' => $info
        ]);

        try {
             DB::transaction(function() use ($request) {
                // throw new Exception("Connect failed!");

                DB::table('order_send')->where('id', $request->record_id)->update([
                    'time' => $request->changed_time . ':00'
                ]);
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function cancelSend(Request $request)
    {
        $record = null;
        if($request->method() != 'POST' || !$request->has('record_id') || ($record = DB::table('order_send')->where('id', $request->record_id)->first()) == null) {
            return back()->withErrors(['找不到配送记录！或者数据库出错！']);
        }

        $info = '取消原因：' . $request->reason;

        SendChange::create([
            'order_id' => $record->order_id,
            'send_id' => $record->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => 4,
            'info' => $info
        ]);

        try {
             DB::transaction(function() use ($request) {
                // throw new Exception("Connect failed!");

                DB::table('order_send')->where('id', $request->record_id)->update([
                    'status' => 3,
                    'remarks' => $request->reason
                ]);
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function backMoney(Request $request)
    {
        $record = null;
        if($request->method() != 'POST' || !$request->has('record_id') || ($record = OrderSend::find($request->record_id)) == null) {
            return back()->withErrors(['找不到配送记录！或者数据库出错！']);
        }

        // try {
        //      DB::transaction(function() use ($request) {
        //         // throw new Exception("Connect failed!");

        //         DB::table('order_send')->where('id', $request->record_id)->update([
        //             'status' => 4,
        //             'remarks' => '退款' . $request->back_money . '元。原因如下：' . $request->reason
        //         ]);
        //      });
        // } catch (\Exception $e) {
        //     return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        // }

        // 退款
        $info = '退款：' . $request->back_money . '元；退款原因：' . $request->reason;
        $order = Order::find($record->order_id);
        $order->num = (int)$order->num - (int)$record->num;
        $order->money = (int)$order->money - (int)$request->back_money;

        SendChange::create([
            'order_id' => $record->order_id,
            'send_id' => $record->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => 6,
            'food_type' => $order->type,
            'back_money' => $request->back_money,
            'info' => $info
        ]);

        if(! $order->saveOrFail()) {
            return back()->withErrors(['订单修改失败！']);
        }
        $record->status = 4;
        $record->remarks = '退款' . $request->back_money . '元。原因如下：' . $request->reason;
        if(! $record->saveOrFail()) {
            return back()->withErrors(['配送记录修改失败！']);
        }

        return back();
    }

    public function backAllMoney(Request $request)
    {
        $order = null;
        if($request->method() != 'POST' || !$request->has('order_id') || ($order = Order::find($request->order_id)) == null) {
            return back()->withErrors(['找不到要退款的订单记录！']);
        }

        $info = '退全款；退款金额：' . $request->back_money . '元';
        SendChange::create([
            'order_id' => $order->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => 7,
            'info' => $info,
            'food_type' => $order->type,
            'back_money' => $request->back_money,
        ]);

        OrderSend::where([
            ['order_id', $order->id],
            ['status', 0]
        ])->update(['status' => 4]);

        $order->money = (int)$order->money - (int)$request->back_money;
        $order->num = OrderSend::where([
            ['order_id', $order->id],
            ['status', 2]
        ])->get()->sum('num');
        $order->status = 2;
        $order->remarks = $request->reason;
        if(! $order->saveOrFail()) {
            return back()->withErrors(['修改失败！']);
        }
        return back();
    }

    public function changeFood(Request $request)
    {
        $record = null;
        if($request->method() != 'POST' || !$request->has('record_id') || ($record = OrderSend::find($request->record_id)) == null) {
            return back()->withErrors(['找不到要修改的记录！']);
        }
        $order = Order::find($record->order_id);

        $food_info = $record->type == 1 ? '男士餐' : ($record->type == 2 ? '女士餐' : '工作餐');
        $changed_food = $request->food_type == 1 ? '男士餐' : ($request->food_type == 2 ? '女士餐' : '工作餐');
        $food_info = $record->num . '份' . $food_info . '   更改为->   ' . $request->food_num . '份' . $changed_food . '。补偿' . $request->need_pay . '元';

        SendChange::create([
            'order_id' => $order->id,
            'send_id' => $record->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => 1,
            'info' => $food_info
        ]);

        $order->money = (int)$order->money + (int)$request->need_pay;
        $order->num = (int)$order->num + (int)$request->food_num - (int)$record->num;

        if(! $order->saveOrFail()) {
            return back()->withErrors(['订单信息修改失败！']);
        }

        $record->type = $request->food_type;
        $record->num = $request->food_num;
        if(! $record->saveOrFail()) {
            return back()->withErrors(['保存失败！']);
        }

        return back();
    }

    public function changeSendStatus(Request $request)
    {
        $record = null;
        if($request->method() != 'POST' || !$request->has('id') || ($record = OrderSend::find($request->id)) == null) {
            return back()->withErrors(['要更新的配送记录不存在！']);
        }

        $type = 1;
        $info = '';
        if($request->status == 1) {
            $type = 5; // 配送中
            $info = '配送中';
        } elseif($request->status == 2) {
            $type = 8; // 已完成
            $info = '已完成';
        }

        SendChange::create([
            'order_id' => $record->order_id,
            'send_id' => $record->id,
            'user_id' => Auth::guard('admin')->user()->id,
            'username' => Auth::guard('admin')->user()->name,
            'type' => $type,
            'info' => $info
        ]);

        $record->status = $request->status;
        if(! $record->saveOrFail()) {
            return back()->withErrors(['更新失败！']);
        }

        return back();
    }

    public function sendChangeInfo(Request $request)
    {
        $order = null;
        if(!$request->has('id') || ($order = Order::find($request->id)) == null) {
            return back()->withErrors(['未找到该订单！']);
        }
        $records = SendChange::where('order_id', $request->id)->orderBy('updated_at', 'desc')->get();
        return view('admin.backend.orders.send-change-info', compact('order', 'records'));
    }

    public function changePayStatus(Request $request)
    {
        $order = null;
        if($request->method() != 'POST' || !$request->has('id') || ($order = Order::find($request->id)) == null) {
            return back()->withErrors(['找不到订单！']);
        }

        $user = User::find($order->user_id);
        $money = $order->money;
        $user->total_pay = $user->total_pay + $money;
        $need_pay_money = $money;

        if($user->remain_money < $need_pay_money) {
            $info = '余额不足！实际应付' . $need_pay_money . '元，账户余额' . $user->remain_money . '元！';
            return back()->withErrors([$info]);
        }

        $num = OrderSend::where('order_id', $order->id)->get()->count();
        if($num == 0 && $order->method == 0) {
            Order::generateSend($order->id);
        }
        
        $user->remain_money = $user->remain_money - $money;
        $o_food_type = ($order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐'));
        RemainMoney::create([
            'user_id' => $user->id,
            'username' => $user->wechat_name,
            'money' => 0 - $money,
            'info' => '购买' . $order->num . '次' . $o_food_type,
        ]);
        $user->score += round($money * Score::getRate());
        $order->score = round($money * Score::getRate());
        Score::create([
            'user_id' => $user->id,
            'status' => 0,
            'order_id' => $order->id,
            'score' => $order->score,
        ]);

        // $recommend_user = null;
        // $recommend = null;
        // if($user->recommend_by != 0 && ($recommend_user = User::find($user->recommend_by)) != null && ($recommend = RecommendPrize::find($user->recommend)) != null) {
        //     if($recommend->status == 1) {
        //         $prize_money = round($order->money * ((double)$recommend->back_money / 100), 2);
        //         $user->prize_money += $prize_money;
        //         $order->is_prize = 1;
        //         $order->prize_money = $prize_money;
        //     }
        // }
        // $recommend = RecommendPrize::where([
        //     ['condition', '<=', $user->total_pay],
        //     ['status', 1]
        // ])->orderBy('condition', 'desc')->first();
        // if($recommend != null) {
        //     $user->recommend = $recommend->id;
        // }

        // $recommend_user = null;
        // if($order->recommend != null && ($recommend_user = User::find($order->user_id)) != null && $recommend_user->recommend != 0) {
        //     $o_recommend = RecommendPrize::find($recommend_user->recommend);
        //     $prize_money = round($order->money * ((double)$o_recommend->back_money / 100), 2);
        //     $user->prize_money += $prize_money;
        //     $order->is_prize = 1;
        //     $order->prize_money = $prize_money;
        // }
        if($order->method == 0) {
            if($order->type == 1) {
                $user->man_times += $order->num;
                // $user->man_remain_times -= $order->use_remain_times;
            } elseif($order->type == 2) {
                $user->woman_times += $order->num;
                // $user->woman_remain_times -= $order->use_remain_times;
            } elseif($order->type == 3) {
                $user->work_times += $order->num;
                // $user->work_remain_times -= $order->use_remain_times;
            }
        } elseif($order->method == 1) {
            if($order->type == 1) {
                $user->man_times += $order->num;
                $user->man_remain_times += $order->num;
            } elseif($order->type == 2) {
                $user->woman_times += $order->num;
                $user->woman_remain_times += $order->num;
            } elseif($order->type == 3) {
                $user->work_times += $order->num;
                $user->work_remain_times += $order->num;
            }
        }
        $user->save();

        $order->pay_status = $request->status;
        if(!$order->saveOrFail()) {
            return back()->withErrors(['储存失败！']);
        }
        return back();
    }

    public function changeStatus(Request $request)
    {
        $order = null;
        if($request->method() != 'POST' || !$request->has('id') || ($order = Order::find($request->id)) == null) {
            return back()->withErrors(['找不到该订单！']);
        }

        $order->status = $request->status;
        if(!$order->saveOrFail()) {
            return back()->withErrors(['储存失败！']);
        }
        return back();
    }

    public function export(Request $request)
    {
        $orders = Order::where('id', '>', 0);
        if($request->has('type') && $request->type != 0) {
            $orders->where('type', $request->type);
        }
        if($request->has('begin_time') && $request->begin_time != null) {
            $orders = $orders->where('created_at', '>=', $request->begin_time);
        }
        if($request->has('end_time') && $request->end_time != null) {
            $orders = $orders->where('created_at', '<=', $request->end_time);
        }
        if($request->has('keyword') && $request->keyword != null && trim($request->keyword) != '') {
            $key = trim($request->keyword);
            if(((int)$key) != 0 && strlen($key) == 13) {
                $orders = $orders->where('id', (int)$key);
            } elseif(((int)$key) != 0) {
                $orders = $orders->where('tel', $key);
            } else {
                $orders = $orders->where('real_name', $key);
            }
        }
        $orders = $orders->orderBy('created_at', 'desc')->get();

        $items = array();
        $item = array('订单号', '配送产品', '订单金额', '收货地址', '付款状态', '订单状态', '收货人', '收货人手机');
        array_push($items, $item);
        foreach($orders as $order) {
            $item = array();
            array_push($item, sprintf('%013d', $order->id));
            $tmp = ($order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐'));
            $tmp .= '：' . $order->num . '份';
            array_push($item, $tmp);
            array_push($item, $order->money);
            array_push($item, $order->address);
            $tmp = ($order->pay_status == 1 ? '已付款' : '未付款');
            array_push($item, $tmp);
            $tmp = ($order->status == 1 ? '已完成' : '进行中');
            array_push($item, $tmp);
            array_push($item, $order->real_name);
            array_push($item, $order->tel);

            array_push($items, $item);
        }

        $title = '订单详情';
        Excel::create($title, function($excel) use($items) {
            $excel->sheet('收入明细', function($sheet) use($items) {
                $sheet->fromArray($items, null, 'A1', true, false);

                $row = count($items);
                $col = count($items[0]);

                $left_top = $sheet->getCellByColumnAndRow(0, 1)->getCoordinate();
                $right_bottom = $sheet->getCellByColumnAndRow($col - 1, $row)->getCoordinate();
                $range = $left_top . ":" . $right_bottom;
                $sheet->cells($range, function($cells) {
                    $cells->setAlignment('center')->setValignment('center');
                });

                for($i = 0; $i < $col; ++$i) {
                    for($j = 0; $j < $row; ++$j) {
                        $left_top = $sheet->getCellByColumnAndRow($i, $j + 1)->getCoordinate();
                        $sheet->getStyle($left_top)->getAlignment()->setWrapText(true);
                        $sheet->setWidth($sheet->getColumnDimensionByColumn($j)->getColumnIndex(), 14);
                    }
                }
            });
        })->export('xls');
    }
}
