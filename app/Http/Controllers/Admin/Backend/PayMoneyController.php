<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PayMoney;
use App\Models\User;
use App\Models\RemainMoney;

class PayMoneyController extends Controller
{
    public function index(Request $request)
    {
    	$pay_moneys = PayMoney::where('id', '>', 0);

    	if($request->method() == 'POST' && $request->has('search') && $request->search == true) {
    		if($request->has('begin_time') && $request->begin_time != null) {
                $pay_moneys = $pay_moneys->where('created_at', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $pay_moneys = $pay_moneys->where('created_at', '<=', $request->end_time);
            }
    	}

    	$user = null;
    	if($request->has('user_id') && ($user = User::find($request->user_id)) != null) {
    		$pay_moneys = $pay_moneys->where('user_id', $request->user_id);
    	}

    	$pay_moneys = $pay_moneys->orderBy('created_at', 'desc');
    	$pay_moneys = $pay_moneys->paginate(env('EACH_PAGE_NUM'));
    	$info = $request->all();

    	return view('admin.backend.pay-money.index', compact('pay_moneys', 'info', 'user'));
    }

    public function changeStatus(Request $request)
    {
        $pay_money = null;
        if($request->method() != 'POST' || !$request->has('id') || ($pay_money = PayMoney::find($request->id)) == null) {
            return back()->withErrors(['找不到记录！']);
        }

        if($request->status == 1) { // 充值确认
            $user = User::find($pay_money->user_id);
            $user->remain_money += $pay_money->money;
            if(!$user->saveOrFail()) {
                return back()->withErrors(['保存失败！']);
            }

            RemainMoney::create([
                'user_id' => $user->id,
                'username' => $user->wechat_name,
                'money' => $pay_money->money,
                'info' => '充值'
            ]);
        }

        $pay_money->status = $request->status;
        if(!$pay_money->saveOrFail()) {
            return back()->withErrors(['保存失败！']);
        }
        return back();
    }
}
