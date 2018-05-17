<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use App\Models\RecommendPrize;
use App\Models\Address;
use App\Models\UserWeight;
use App\Models\RemainMoney;
use App\Models\Food;
use DB, Auth;
use Exception;
use Carbon\Carbon;

class UserController extends Controller
{ 
    public function index(Request $request)
    {
        $users = User::where('id', '>', 0);
        $keyword = null;
        if($request->has('keyword') && $request->keyword != null) {
            $keyword = '%' . $request->input('keyword') . '%';
            $users = $users->where('id', $keyword)->orWhere('real_name', 'like', $keyword)->orWhere('tel', 'like', $keyword);
            $keyword = $request->input('keyword');
        }
        if($request->has('sort_field')) {
            $users = $users->orderBy($request->sort_field, $request->sort_field_by);
        }else{
            $users = $users->orderBy("id", "desc");
        }

        $users = $users->paginate(env('EACH_PAGE_NUM'));
    	
    	return view('admin.backend.users.index', compact('users', 'keyword'));
    }

    public function show(Request $request)
    {
        $user = null;
        if(! $request->has('id') || ($user = User::find($request->input('id'))) == null) {
            return back()->withErrors(['用户不存在！']);
        }

        $user->man_amend_times = DB::table('order_amend')->where([
            ['user_id', $user->id],
            ['type', 1]
        ])->count();

        $user->woman_amend_times = DB::table('order_amend')->where([
            ['user_id', $user->id],
            ['type', 2]
        ])->count();

        $user->work_amend_times = DB::table('order_amend')->where([
            ['user_id', $user->id],
            ['type', 3]
        ])->count();

        $recommend = null;
        if($user->recommend != 0) {
            $recommend = RecommendPrize::find($user->recommend);
        }

        $man_price = Food::getFoodPrice(1);
        $woman_price = Food::getFoodPrice(2);
        $work_price = Food::getFoodPrice(3);

        return view('admin.backend.users.show', compact('user', 'recommend', 'man_price', 'woman_price', 'work_price'));
    }

    public function edit(Request $request)
    {
        $user = null;
        if(! $request->has('id') || ($user = User::find($request->input('id'))) == null) {
            return back();
        }

        $addresses = Address::where('user_id', $user->id)->get();

        return view('admin.backend.users.edit', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $user = null;
        if($request->method() != 'POST' || !$request->has('id') || ($user = User::find($request->input('id'))) == null) {
            return back();
        }

        $address = $request->address;

        if($address == -1) {
            $address = $request->full_address;
            Address::create([
                'user_id' => $user->id,
                'address' => $address,
                'username' => $user->real_name,
                'tel' => $user->tel
            ]);
        }

        $user->real_name = $request->real_name;
        $user->gender = $request->gender;
        $user->birthday = $request->birthday;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->tel = $request->tel;
        $user->address = $address;

        if(! $user->saveOrFail()) {
            return back()->withErrors(['用户信息修改失败！']);
        }

        return redirect(U('Backend/User/index'));
    }

    public function changeTimes(Request $request)
    {
        $user = null;
        if($request->method() != 'POST' || !$request->has('id') || ($user = User::find($request->input('id'))) == null) {
            return back()->withErrors(['产生错误，可能是用户不存在！']);
        }

        $val = (int) $request->input('change_times');
        $type = 1;
        $origin_times = 0;

        if($request->input('food-type') == '男士餐') {
            $type = 1;
            $origin_times = $user->man_remain_times;
            $user->man_remain_times += $val;
        } else if($request->input('food-type') == '女士餐') {
            $type = 2;
            $origin_times = $user->woman_remain_times;
            $user->woman_remain_times += $val;
        } else if($request->input('food-type') == '工作餐') {
            $type = 3;
            $origin_times = $user->work_remain_times;
            $user->work_remain_times += $val;
        }

        if($request->is_minus == 1) {
            $user->remain_money -= round((double)$request->back_money_input, 2);

            RemainMoney::create([
                'user_id' => $user->id,
                'username' => $user->wechat_name,
                'money' => 0 - round((double)$request->back_money_input, 2),
                'info' => '增加' . $val . '次' . $request->input('food-type')
            ]);
        } 

        if(! $user->saveOrFail()) {
            return back()->withErrors(['更新失败！']);
        }

        $time = Carbon::now();

        try {
            DB::transaction(function() use ($user, $request, $type, $origin_times, $val, $time) {

                DB::table('order_amend')->insert([
                    'user_id' => $user->id,
                    'amend_user_id' => Auth::guard('admin')->user()->id,
                    'amend_user_name' => Auth::guard('admin')->user()->name,
                    'type' => $type,
                    'origin_times' => $origin_times,
                    'amend_times' => $val,
                    'reason' => $request->input('reason'),
                    'created_at' => $time,
                    'updated_at' => $time
                ]);

                //throw new Exception("Connect failed!");
            });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function delete(Request $request)
    {
        $user = null;
        if($request->method() != 'POST' || ! $request->has('id') || ($user = User::find($request->id)) == null) {
            return back()->withErrors(['未找到要删除的用户！']);
        }

        try {
             DB::transaction(function() use ($user) {
                //throw new Exception("Connect failed!");

                DB::table('addresses')->where('user_id', $user->id)->delete();
                DB::table('coupon_user')->where('user_id', $user->id)->delete();
                $orders = DB::table('orders')->where('user_id', $user->id)->get();
                foreach($orders as $order) {
                    DB::table('order_amend')->where('order_id', $order->id)->delete();
                }
                DB::table('orders')->where('user_id', $user->id)->delete();
                DB::table('order_send')->where('user_id', $user->id)->delete();

                $user->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['删除时数据库发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function weight(Request $request)
    {
        $user = null;
        if(!$request->has('user_id') || ($user = User::find($request->user_id)) == null) {
            return back();
        }

        $weights = UserWeight::where('user_id', $request->user_id);
        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {
            if($request->has('begin_time') && $request->begin_time != null) {
                $weights = $weights->where('created_at', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $weights = $weights->where('created_at', '<=', $request->end_time);
            }
        }

        $weights = $weights->orderBy('created_at', 'desc')->paginate(env('EACH_PAGE_NUM'));

        return view('admin.backend.users.weight', compact('weights', 'user'));
    }
}
