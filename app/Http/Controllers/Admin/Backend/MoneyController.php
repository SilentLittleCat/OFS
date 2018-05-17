<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BackMoney;
use App\Models\User;
use App\Models\RemainMoney;

class MoneyController extends Controller
{
    public function index(Request $request)
    {
    	$moneys = BackMoney::where('id', '>', 0);
    	if($request->has('num') && $request->num != null) {
    		$moneys = $moneys->where('id', (int)$request->num);
    	}
    	if($request->has('status') && $request->status != -1) {
    		$moneys = $moneys->where('status', $request->status);
    	}
    	if($request->has('username') && $request->username != null) {
    		$moneys = $moneys->where('username', $request->username);
    	}
    	if($request->has('tel') && $request->tel != null) {
    		$moneys = $moneys->where('tel', $request->tel);
    	}

        if($request->has('user_id')) {
            $moneys = $moneys->where('user_id', $request->user_id);
        }

    	$moneys = $moneys->orderBy('created_at', 'desc')->paginate(env('EACH_PAGE_NUM'));

    	return view('admin.backend.moneys.index', compact('moneys', 'request'));
    }

    public function changeStatus(Request $request)
    {
    	$money = null;
    	if($request->method() != 'POST' || !$request->has('id') || ($money = BackMoney::find($request->id)) == null) {
    		return back()->withErrors(['找不到要更新的提现记录！']);
    	}

    	$money->status = $request->status;

        if($money->status == 2) {
            $user = User::find($money->user_id);
            if($user->remain_money < $money->money) {
                $text = '当前余额：' . $user->remain_money . '元！提现金额：' . $money->money . '元';
                return back()->withErrors([$text]);
            }
            $user->remain_money -= $money->money;
            if(! $user->saveOrFail()) {
                return back()->withErrors(['提现失败！']);
            }

            RemainMoney::create([
                'user_id' => $user->id,
                'username' => $user->wechat_name,
                'money' => 0 - $money->money,
                'info' => '提现'
            ]);
        }

    	if(! $money->saveOrFail()) {
    		return back()->withErrors(['更新失败！']);
    	}

    	return back();
    }
}
