<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseAttachmentModel;
use App\Models\User;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Good;
use App\Models\Score;
use App\Models\Order;
use App\Models\ContactUs;
use App\Models\UserWeight;
use App\Models\RecommendPrize;
use App\Models\RemainMoney;
use App\Models\SendRange;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    public function index()
    {
    	$page = 'user';
    	$page_label = '我的';
        $title = '我的';
    	return view('web.users.index', compact('page', 'page_label', 'title'));
    }

    public function info()
    {
    	$page = 'user';
    	$page_label = '我的资料';
        $title = '我的资料';
    	$back_url = route('users.index');

    	return view('web.users.info', compact('page', 'page_label', 'back_url', 'title'));
    }

    public function edit()
    {
    	$page = 'user';
    	$page_label = '修改我的资料';
        $title = '修改我的资料';
    	$back_url = route('users.info');
    	
    	return view('web.users.edit', compact('page', 'page_label', 'back_url', 'title'));
    }

    public function address()
    {
    	$page = 'user';
    	$page_label = '我的收货地址';
        $title = '我的收货地址';
    	$back_url = route('users.index');
    	$hide_bottom = true;
        $addresses = Address::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
    	
    	return view('web.users.address', compact('page', 'page_label', 'back_url', 'hide_bottom', 'title', 'addresses'));
    }

    public function addAddress()
    {
        $page = 'user';
        $page_label = '添加收货地址';
        $title = '添加收货地址';
        $back_url = route('users.address', 'title');
        $cities = SendRange::get()->unique('city');
        $counties = collect();
        if($cities != null && $cities->count() != 0) {
            $counties = SendRange::where('city', $cities->first()->city)->get();
        }
        $all_counties = SendRange::get();
        
        return view('web.users.add-address', compact('page', 'page_label', 'back_url', 'hide_bottom', 'title', 'cities', 'counties', 'all_counties'));
    }

    public function storeAddress(Request $request)
    {
        Address::create([
            'user_id' => Auth::user()->id,
            'address' => $request->full_address,
            'username' => $request->username,
            'tel' => $request->tel
        ]);

        return redirect()->route('users.address');
    }

    public function score()
    {
        $page = 'user';
        $page_label = '积分商城';
        $title = '积分商城';
        $back_url = route('users.index');
        $hide_bottom = true;
        $goods = Good::orderBy('updated_at', 'desc')->get();
        
        return view('web.users.score', compact('page', 'page_label', 'back_url', 'hide_bottom', 'goods', 'title'));
    }

    public function scoreChange(Request $request)
    {
        $good = null;
        if(!$request->has('good_id') || ($good = Good::find($request->good_id)) == null || $good->score > Auth::user()->score) {
            return back();
        }

        if($good->type == 0) { //兑换实物
            Score::create([
                'user_id' => Auth::user()->id,
                'status' => 1,
                'name' => $good->name,
                'score' => $good->score
            ]);
        } elseif($good->type == 1) {
            // 兑换优惠券
            $coupon = Coupon::find($good->coupon_id);
            if($coupon == null || $coupon->status == 0 || $coupon->use_total >= $coupon->total) {
                return back()->withErrors(['优惠券发放完毕！']);
            }
            $coupon->use_total += 1;
            if($coupon->use_total >= $coupon->total) {
                $coupon->status = 0;
                $coupon->save();
            }
            Score::create([
                'user_id' => Auth::user()->id,
                'status' => 1,
                'name' => $good->name,
                'score' => $good->score,
                'coupon_id' => $good->coupon_id,
            ]);

            DB::table('coupon_user')->insert([
                'user_id' => Auth::user()->id,
                'coupon_id' => $good->coupon_id,
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->score -= $good->score;
        if($user->saveOrFail()) {
            return back()->withErrors(['积分使用失败！']);
        }

        return back();
    }

    public function showScoreRecord()
    {
        $page = 'user';
        $page_label = '积分记录';
        $title = '积分记录';
        $back_url = route('users.score');
        $records = Score::where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
        
        return view('web.users.score-record', compact('page', 'page_label', 'back_url', 'hide_bottom', 'records', 'title'));
    }

    public function showScoreChange()
    {
        $page = 'user';
        $page_label = '兑换记录';
        $title = '兑换记录';
        $back_url = route('users.score');
        $records = Score::where([
            ['user_id', Auth::user()->id],
            ['status', 1]
        ])->orderBy('updated_at', 'desc')->get();
        
        return view('web.users.score-change', compact('page', 'page_label', 'back_url', 'hide_bottom', 'records', 'title'));
    }

    public function recommend()
    {
        $page = 'user';
        $page_label = '推荐有礼';
        $title = '推荐有礼';
        $back_url = route('users.index');
        $recommends = RecommendPrize::all();
        $my_recommend = null;
        if(Auth::user()->recommend != 0) {
            $my_recommend = RecommendPrize::find(Auth::user()->recommend);
        }
        $two_code_img = BaseAttachmentModel::where('class', '二维码')->first();

        return view('web.users.recommend', compact('page', 'page_label', 'back_url', 'title', 'two_code_img', 'recommends', 'my_recommend'));
    }

    public function coupon()
    {
        $page = 'user';
        $page_label = '我的优惠券';
        $title = '我的优惠券';
        $back_url = route('users.index');
        $coupons = User::find(Auth::user()->id)->coupons()->where([
            ['coupon_user.status', 0]
        ])->get();
        
        return view('web.users.coupon', compact('page', 'page_label', 'back_url', 'title', 'coupons'));
    } 

    public function contact()
    {
        $page = 'user';
        $page_label = '联系我们';
        $title = '联系我们';
        $back_url = route('users.index');
        $tel = ContactUs::getTel();
        $two_code = ContactUs::getTwoCode();
        $two_code_img = BaseAttachmentModel::where('class', '二维码')->first();
        
        return view('web.users.contact', compact('page', 'page_label', 'back_url', 'title', 'two_code_img', 'tel', 'two_code'));
    } 

    public function update(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $user->real_name = $request->real_name;
        $user->gender = $request->birthday;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->tel = $request->tel;

        UserWeight::create([
            'user_id' => Auth::user()->id,
            'username' => Auth::user()->wechat_name,
            'weight' => $request->weight
        ]);

        if(! $user->saveOrFail()) {
            return back()->withErrors(['修改失败！']);
        }

        return redirect()->route('users.info');
    }

    public function weight(Request $request)
    {
        $page = 'user';
        $page_label = '体重记录';
        $title = '体重记录';
        $back_url = route('users.info');
        $weights = UserWeight::where('user_id', Auth::user()->id)->orderBy('created_at')->get();

        $weight_arr = $weights->pluck('weight')->toArray();
        $date_arr = array();

        foreach($weights as $weight) {
            $date = $weight->created_at;
            $item = $date->year . '/' . $date->month . '/' . $date->day;
            array_push($date_arr, $item);
        }

        $weight_arr = implode("|", $weight_arr);
        $date_arr = implode("|", $date_arr);

        $weights = $weights->sortByDesc('created_at');

        return view('web.users.weight', compact('weights', 'page', 'page_label', 'title', 'back_url', 'weight_arr', 'date_arr'));
    }

    public function remainMoney(Request $request)
    {
        $page = 'user';
        $page_label = '余额流水记录';
        $title = '余额流水记录';
        $back_url = route('users.index');
        $records = RemainMoney::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('web.users.remain-money', compact('records', 'page', 'page_label', 'title', 'back_url'));
    }
}
