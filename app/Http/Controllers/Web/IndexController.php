<?php

namespace App\Http\Controllers\Web;

use App\Models\BaseAttachmentModel;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Activity;
use App\Models\Address;
use App\Models\Good;
use App\Models\SendRange;
use App\Models\SendDate;
use App\Models\FoodSet;
use Auth;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index(Request $request)
    {
    	$carousels = Good::all();
        $man_food = Food::where('name', '男士餐')->first();
        $woman_food = Food::where('name', '女士餐')->first();
        $work_food = Food::where('name', '工作餐')->first();
        $foods = Food::where('status', 1)->get();
        $man_remain_times = 0;
        $woman_remain_times = 0;
        $work_remain_times = 0;
        $man_food_price = (int)(Food::getFoodPrice(1));
        $woman_food_price = (int)(Food::getFoodPrice(2));
        $work_food_price = (int)(Food::getFoodPrice(3));
        $addresses = collect([]);

        $dates = SendDate::where('status', 0)->get();
        $forbid_dates = '';
        $len = count($dates);
        $now = Carbon::now()->toDateString();
        foreach($dates as $key => $val) {
            $forbid_dates .= $val->date;
            if($key != $len - 1) {
                $forbid_dates .= ',';
            }
        }

        $five_days = collect();
        $count_tmp = 1;
        while($five_days->count() < 5) {
            $tmp_day = Carbon::now()->addDays($count_tmp);
            $tmp_date = $tmp_day->toDateString();
            if(! $tmp_day->isWeekend() && !$dates->contains('date', $tmp_date)) {
                $five_days->push($tmp_date);
            }
            ++$count_tmp;
        }

        $five_days = $five_days->implode(',');

        $twenty_days = collect();
        $count_tmp = 1;
        while($twenty_days->count() < 20) {
            $tmp_day = Carbon::now()->addDays($count_tmp);
            $tmp_date = $tmp_day->toDateString();
            if(! $tmp_day->isWeekend() && !$dates->contains('date', $tmp_date)) {
                $twenty_days->push($tmp_date);
            }
            ++$count_tmp;
        }

        $twenty_days = $twenty_days->implode(',');

        if(Auth::check()) {
            $man_remain_times = Auth::user()->man_remain_times ?: $man_remain_times;
            $woman_remain_times = Auth::user()->woman_remain_times ?: $woman_remain_times;
            $work_remain_times = Auth::user()->work_remain_times ?: $work_remain_times;

            $addresses = Address::where('user_id', Auth::user()->id)->get();
        }
    	$page = 'index';
        $page_label = '一周预定';
        $title = '首页';

        $nums = collect();
        for($i = 1; $i <= 30; ++$i) {
            $nums->push($i);
        }
        $recommend = $request->has('recommend') ? $request->recommend : 0;

        $cities = SendRange::get()->unique('city');
        $counties = collect();
        if($cities != null && $cities->count() != 0) {
            $counties = SendRange::where('city', $cities->first()->city)->get();
        }
        $all_counties = SendRange::get();

        $food_sets = FoodSet::where('status', 1)->orderBy('sort')->get();
        $send_money = Food::getSendMoney();

    	return view('web.index.index', compact('page', 'page_label', 'carousels', 'foods', 'title', 'man_remain_times', 'woman_remain_times', 'work_remain_times', 'nums', 'addresses', 'man_food_price', 'woman_food_price', 'work_food_price', 'recommend', 'forbid_dates', 'now', 'cities', 'counties', 'all_counties', 'food_sets', 'five_days', 'twenty_days', 'send_money'));
    }

    public function introduce()
    {
    	// $man_food_img = BaseAttachmentModel::where('class', '男士餐图')->first();
    	// $woman_food_img = BaseAttachmentModel::where('class', '女士餐图')->first();
    	// $work_food_img = BaseAttachmentModel::where('class', '工作餐图')->first();

        $foods = Food::where('status', 1)->get();

    	$page = 'introduce';
        $page_label = '餐类介绍';
        $title = '餐类介绍';

    	return view('web.index.introduce', compact('page', 'page_label', 'foods', 'title'));
    }

    public function activity()
    {
    	// $man_food_img = BaseAttachmentModel::where('class', '男士餐图')->first();
    	// $woman_food_img = BaseAttachmentModel::where('class', '女士餐图')->first();
    	// $work_food_img = BaseAttachmentModel::where('class', '工作餐图')->first();

        $man_food = Food::where([
            ['name', '男士餐'],
            ['status', 1]
        ])->first();
        $woman_food = Food::where([
            ['name', '女士餐'],
            ['status', 1]
        ])->first();
        $work_food = Food::where([
            ['name', '工作餐'],
            ['status', 1]
        ])->first();

        $man_acts = Activity::where([
            ['status', 1],
            ['type', 0],
            ['begin_time', '<=', Carbon::now()->toDateString()],
            ['end_time', '>=', Carbon::now()->toDateString()],
        ])->orWhere([
            ['status', 1],
            ['type', 1],
            ['begin_time', '<=', Carbon::now()->toDateString()],
            ['end_time', '>=', Carbon::now()->toDateString()],
        ])->get();
        $woman_acts = Activity::where([
            ['status', 1],
            ['type', 0],
            ['begin_time', '<=', Carbon::now()->toDateString()],
            ['end_time', '>=', Carbon::now()->toDateString()],
        ])->orWhere([
            ['status', 1],
            ['type', 2],
            ['begin_time', '<=', Carbon::now()->toDateString()],
            ['end_time', '>=', Carbon::now()->toDateString()],
        ])->get();
        $work_acts = Activity::where([
            ['status', 1],
            ['type', 0],
            ['begin_time', '<=', Carbon::now()->toDateString()],
            ['end_time', '>=', Carbon::now()->toDateString()],
        ])->orWhere([
            ['status', 1],
            ['type', 3],
            ['begin_time', '<=', Carbon::now()->toDateString()],
            ['end_time', '>=', Carbon::now()->toDateString()],
        ])->get();
        $man_food_price = (int)(Food::getFoodPrice(1));
        $woman_food_price = (int)(Food::getFoodPrice(2));
        $work_food_price = (int)(Food::getFoodPrice(3));

    	$page = 'activity';
        $page_label = '优惠活动';
        $title = '会员活动';

    	return view('web.index.activity', compact('page', 'page_label', 'man_food', 'woman_food', 'work_food', 'man_acts', 'woman_acts', 'work_acts', 'title', 'man_food_price', 'woman_food_price', 'work_food_price'));
    }
}
