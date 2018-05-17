<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Score;
use App\Models\BackMoney;
use App\Models\PayMoney;
use App\Models\ContactUs;
use App\Models\BaseAttachmentModel;
use App\Services\Base\Attachment;
use Auth;

class MoneyController extends Controller
{
    private $_serviceAttachment;
    public function __construct()
    {
        if( !$this->_serviceAttachment ) $this->_serviceAttachment = new Attachment();

    }
    /**
     *
     */
    public function index(Request $request)
    {
    	return view('web.money.index', $request->all());
    }

    public function payIndex(Request $request)
    {
        $page = 'user';
        $page_label = '充值';
        $title = '充值';
        $back_url = route('users.index');
        return view('web.money.pay-index', compact('page', 'page_label', 'title', 'back_url'));
    }

    public function backIndex(Request $request)
    {
        $page = 'user';
        $page_label = '提现';
        $title = '提现';
        $back_url = route('users.recommend');
        
        return view('web.orders.money', compact('page', 'page_label', 'back_url', 'title'));
    }

    public function payConfirm(Request $request)
    {
        if($request->method() != 'POST' || !$request->has('money')) {
            return back();
        }

        $money = round((double)$request->money, 2);
        if($money == 0.00) {
            return back()->withErrors(['充值金额有错！']);
        }

        PayMoney::create([
            'money' => $money,
            'status' => 0,
            'user_id' => Auth::user()->id,
            'username' => Auth::user()->wechat_name
        ]);

        return redirect()->route('money.pay.code');
    }

    public function payCode(Request $request)
    {
        $page = 'user';
        $page_label = '扫码付款';
        $title = '扫码付款';
        $back_url = route('users.index');
        $pay_code = ContactUs::where('key', 'pay_code')->first();
        return view('web.money.pay-code', compact('page', 'page_label', 'title', 'back_url', 'pay_code'));
    }

    public function pay(Request $request)
    {
        dd($request->all());

        //更新用户recommend,prize_money,remain_money,total_pay,total_consume,score
        //更新积分记录Score
        return view('web.money.index', $request->all());
    }

    public function back(Request $request)
    {

        if($request->method() != 'POST' || !$request->has('back_money') || !$request->hasFile('wechat_code_img')) {
            return back();
        }

        $tmp = $request->all();
        $tmp['folder'] = '/upload/wechat_code';
        $data = $this->_serviceAttachment->localUpload('wechat_code_img', $tmp, 'files');

        if($data['code'] != 200) {
            return back();
        } 

        $request->wechat_code_img = $data['fileurl'];

        $back_money = round((double)$request->back_money, 2);
        $total_money = round((double)Auth::user()->remain_money, 2);
        if($back_money > $total_money || $back_money == 0.00) {
            return back()->withErrors(['提现金额不能超过账户余额！']);
        }


        BackMoney::create([
            'money' => $request->back_money,
            'status' => 0,
            'user_id' => Auth::user()->id,
            'username' => Auth::user()->wechat_name,
            'tel' => Auth::user()->tel,
            'img' => $request->wechat_code_img
        ]);
        return redirect()->route('users.index');
    }
}
