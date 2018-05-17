<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\Scene;
use App\Models\BuyClass;
use App\Models\CostClass;
use App\Models\WarnLine;
use App\Models\Inventory;
use App\Models\CostRange;
use App\Models\ContactUs;
use App\Models\Food;
use App\Models\RecommendPrize;
use DB;

class ManageController extends Controller
{
    public function scene(Request $request)
    {
    	$scenes = Scene::orderBy('sort')->get();

    	return view('admin.backend.manages.scene', compact('scenes'));
    }

    public function changeSceneStatus(Request $request)
    {
    	$scene = null;
    	if($request->method() != 'POST' || !$request->has('id') || !$request->has('status') || ($scene = Scene::find($request->id)) == null) {
    		return back()->withErrors(['场景分类不存在！']);
    	}

    	$scene->status = $request->status;
        try {
             DB::transaction(function() use ($scene) {
                // throw new Exception("Connect failed!");
                $scene->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function storeScene(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back();
    	}
    	$scene = new Scene;
    	$scene->name = $request->name;
    	$scene->sort = $request->sort;
    	$scene->status = $request->status;

        try {
             DB::transaction(function() use ($scene) {
                // throw new Exception("Connect failed!");
                $scene->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function updateScene(Request $request)
    {
    	$scene = null;
    	if($request->method() != 'POST' || !$request->has('id') || ($scene = Scene::find($request->id)) == null) {
    		return back()->withErrors(['场景分类不存在！']);
    	}

    	$scene->name = $request->name;
    	$scene->status = $request->status;
    	$scene->sort = $request->sort; 
        try {
             DB::transaction(function() use ($scene) {
                // throw new Exception("Connect failed!");
                $scene->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteScene(Request $request)
    {
    	$scene = null;
    	if($request->method() != 'POST' || !$request->has('id') || ($scene = Scene::find($request->id)) == null) {
    		return back()->withErrors(['场景分类不存在！']);
    	}

        try {
             DB::transaction(function() use ($scene) {
                // throw new Exception("Connect failed!");
                $scene->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    // 采购分类主页
    public function buyClass(Request $requsest)
    {
    	$buy_classes = BuyClass::orderBy('sort')->get();
    	$fa_classes = BuyClass::where('sort', 0)->get();

    	return view('admin.backend.manages.buy-class', compact('buy_classes', 'fa_classes'));
    }

    public function changeBuyClassStatus(Request $request)
    {
    	$buy_class = null;
    	if($request->method() != 'POST' || !$request->has('id') || !$request->has('status') || ($buy_class = BuyClass::find($request->id)) == null) {
    		return back()->withErrors(['进库采购分类不存在！']);
    	}

    	$buy_class->status = $request->status;
        try {
             DB::transaction(function() use ($buy_class) {
                // throw new Exception("Connect failed!");
                $buy_class->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function storeBuyClass(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back();
    	}
    	$buy_class = new BuyClass;
    	$buy_class->name = $request->name;
    	$buy_class->sort = $request->sort;
    	$buy_class->status = $request->status;
    	if(((int)$buy_class->sort) != 0) {
    		$buy_class->fa_class = $request->fa_class;
    	}
    	$buy_class->is_direct_cost = $request->is_direct_cost;

        try {
             DB::transaction(function() use ($buy_class) {
                // throw new Exception("Connect failed!");
                $buy_class->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function updateBuyClass(Request $request)
    {
    	$buy_class = null;
    	if($request->method() != 'POST' || !$request->has('id') || ($buy_class = BuyClass::find($request->id)) == null) {
    		return back()->withErrors(['进库采购分类不存在！']);
    	}

    	$buy_class->name = $request->name;
    	$buy_class->sort = $request->sort;
    	$buy_class->status = $request->status;
    	if(((int)$buy_class->sort) != 0) {
    		$buy_class->fa_class = $request->fa_class;
    	} else {
    		$buy_class->fa_class = null;
    	}
    	$buy_class->is_direct_cost = $request->is_direct_cost;
        try {
             DB::transaction(function() use ($buy_class) {
                // throw new Exception("Connect failed!");
                $buy_class->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteBuyClass(Request $request)
    {
    	$buy_class = null;
    	if($request->method() != 'POST' || !$request->has('id') || ($buy_class = BuyClass::find($request->id)) == null) {
    		return back()->withErrors(['进库采购分类不存在！']);
    	}

        try {
             DB::transaction(function() use ($buy_class) {
                // throw new Exception("Connect failed!");
                $buy_class->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function cost(Request $request)
    {
        $cost_classes = CostClass::orderBy('sort')->get();

        return view('admin.backend.manages.cost', compact('cost_classes'));
    }
    
    public function changeCostClassStatus(Request $request)
    {
        $cost_class = null;
        if($request->method() != 'POST' || !$request->has('id') || !$request->has('status') || ($cost_class = CostClass::find($request->id)) == null) {
            return back()->withErrors(['运营成本分类不存在！']);
        }

        $cost_class->status = $request->status;
        try {
             DB::transaction(function() use ($cost_class) {
                // throw new Exception("Connect failed!");
                $cost_class->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function storeCostClass(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }
        $cost_class = new CostClass;
        $cost_class->name = $request->name;
        $cost_class->sort = $request->sort;
        $cost_class->status = $request->status;

        try {
             DB::transaction(function() use ($cost_class) {
                // throw new Exception("Connect failed!");
                $cost_class->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function updateCostClass(Request $request)
    {
        $cost_class = null;
        if($request->method() != 'POST' || !$request->has('id') || ($cost_class = CostClass::find($request->id)) == null) {
            return back()->withErrors(['运营成本分类不存在！']);
        }

        $cost_class->name = $request->name;
        $cost_class->sort = $request->sort;
        $cost_class->status = $request->status;
        try {
             DB::transaction(function() use ($cost_class) {
                // throw new Exception("Connect failed!");
                $cost_class->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteCostClass(Request $request)
    {
        $cost_class = null;
        if($request->method() != 'POST' || !$request->has('id') || ($cost_class = CostClass::find($request->id)) == null) {
            return back()->withErrors(['运营成本分类不存在！']);
        }

        try {
             DB::transaction(function() use ($cost_class) {
                // throw new Exception("Connect failed!");
                $cost_class->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function warnLine(Request $request)
    {
        $warn_items = Inventory::select(['name'])->orderBy('updated_at', 'desc')->get()->flatten()->unique();
        $warn_lines = WarnLine::orderBy('updated_at', 'desc')->get();

        return view('admin.backend.manages.warn-line', compact('warn_items', 'warn_lines'));
    }

    public function changeWarnLineStatus(Request $request)
    {
        $warn_line = null;
        if($request->method() != 'POST' || !$request->has('id') || !$request->has('status') || ($warn_line = WarnLine::find($request->id)) == null) {
            return back()->withErrors(['库存警戒线不存在！']);
        }

        $warn_line->status = $request->status;
        try {
             DB::transaction(function() use ($warn_line) {
                // throw new Exception("Connect failed!");
                $warn_line->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function storeWarnLine(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }
        $warn_line = new WarnLine;
        $warn_line->name = $request->name;
        $warn_line->line = $request->line;
        $warn_line->info = $request->info;
        $warn_line->status = $request->status;

        try {
             DB::transaction(function() use ($warn_line) {
                // throw new Exception("Connect failed!");
                $warn_line->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function updateWarnLine(Request $request)
    {
        $warn_line = null;
        if($request->method() != 'POST' || !$request->has('id') || ($warn_line = WarnLine::find($request->id)) == null) {
            return back()->withErrors(['库存警戒线不存在！']);
        }

        $warn_line->name = $request->name;
        $warn_line->line = $request->line;
        $warn_line->info = $request->info;
        $warn_line->status = $request->status;
        try {
             DB::transaction(function() use ($warn_line) {
                // throw new Exception("Connect failed!");
                $warn_line->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteWarnLine(Request $request)
    {
        $warn_line = null;
        if($request->method() != 'POST' || !$request->has('id') || ($warn_line = WarnLine::find($request->id)) == null) {
            return back()->withErrors(['库存警戒线不存在！']);
        }

        try {
             DB::transaction(function() use ($warn_line) {
                // throw new Exception("Connect failed!");
                $warn_line->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }


    public function costRange(Request $request)
    {
        $cost_classes = BuyClass::where('status', 1)->select(['name'])->orderBy('updated_at', 'desc')->get();
        $cost_ranges = CostRange::orderBy('updated_at', 'desc')->get();

        return view('admin.backend.manages.cost-range', compact('cost_classes', 'cost_ranges'));
    }

    public function changeCostRangeStatus(Request $request)
    {
        $cost_range = null;
        if($request->method() != 'POST' || !$request->has('id') || !$request->has('status') || ($cost_range = CostRange::find($request->id)) == null) {
            return back()->withErrors(['成本区间不存在！']);
        }

        $cost_range->status = $request->status;
        try {
             DB::transaction(function() use ($cost_range) {
                // throw new Exception("Connect failed!");
                $cost_range->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function storeCostRange(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }
        $cost_range = new CostRange;
        $cost_range->name = $request->name;
        $cost_range->range_min = $request->range_min;
        $cost_range->range_max = $request->range_max;
        $cost_range->info = $request->info;
        $cost_range->status = $request->status;

        try {
             DB::transaction(function() use ($cost_range) {
                // throw new Exception("Connect failed!");
                $cost_range->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function updateCostRange(Request $request)
    {
        $cost_range = null;
        if($request->method() != 'POST' || !$request->has('id') || ($cost_range = CostRange::find($request->id)) == null) {
            return back()->withErrors(['成本区间不存在！']);
        }

        $cost_range->name = $request->name;
        $cost_range->range_min = $request->range_min;
        $cost_range->range_max = $request->range_max;
        $cost_range->info = $request->info;
        $cost_range->status = $request->status;
        try {
             DB::transaction(function() use ($cost_range) {
                // throw new Exception("Connect failed!");
                $cost_range->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteCostRange(Request $request)
    {
        $cost_range = null;
        if($request->method() != 'POST' || !$request->has('id') || ($cost_range = CostRange::find($request->id)) == null) {
            return back()->withErrors(['成本区间不存在！']);
        }

        try {
             DB::transaction(function() use ($cost_range) {
                // throw new Exception("Connect failed!");
                $cost_range->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function contactUs(Request $request)
    {
        $tel = ContactUs::where('key', 'tel')->first();
        $two_code = ContactUs::where('key', 'two_code')->first();
        return view('admin.backend.manages.contact-us', compact('tel', 'two_code'));
    }

    public function payCode(Request $request)
    {
        $pay_code = ContactUs::where('key', 'pay_code')->first();
        return view('admin.backend.manages.pay-code', compact('pay_code'));
    }

    public function updateContactUs(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }

        $tel = ContactUs::firstOrNew(['key' => 'tel']);
        $tel->val = $request->tel;
        if(! $tel->saveOrFail()) {
            return back()->withErrors(['更新失败！']);
        }

        if($request->change_two_code == 1) {
            $two_code = ContactUs::firstOrNew(['key' => 'two_code']);
            $two_code->val = $request->two_code;
            if(! $two_code->saveOrFail()) {
                return back();
            }
        }

        return back();
    }

    public function updatePayCode(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }

        ContactUs::updateOrCreate(['key' => 'pay_code'], ['val' => $request->pay_code]);

        return back();
    }

    public function recommend(Request $request)
    {
        $recommends = RecommendPrize::orderBy('updated_at', 'desc')->get();

        return view('admin.backend.manages.recommend', compact('recommends'));
    }

    public function changeRecommendStatus(Request $request)
    {
        $recommend = null;
        if($request->method() != 'POST' || !$request->has('id') || !$request->has('status') || ($recommend = RecommendPrize::find($request->id)) == null) {
            return back()->withErrors(['推荐有礼不存在！']);
        }

        $recommend->status = $request->status;
        try {
             DB::transaction(function() use ($recommend) {
                // throw new Exception("Connect failed!");
                $recommend->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function storeRecommend(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }
        $recommend = new RecommendPrize;
        $recommend->name = $request->name;
        $recommend->condition = $request->condition;
        $recommend->back_money = $request->back_money;
        $recommend->info = $request->info;
        $recommend->status = $request->status;

        try {
             DB::transaction(function() use ($recommend) {
                // throw new Exception("Connect failed!");
                $recommend->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function updateRecommend(Request $request)
    {
        $recommend = null;
        if($request->method() != 'POST' || !$request->has('id') || ($recommend = RecommendPrize::find($request->id)) == null) {
            return back()->withErrors(['推荐有礼不存在！']);
        }

        $recommend->name = $request->name;
        $recommend->condition = $request->condition;
        $recommend->back_money = $request->back_money;
        $recommend->info = $request->info;
        $recommend->status = $request->status;
        try {
             DB::transaction(function() use ($recommend) {
                // throw new Exception("Connect failed!");
                $recommend->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteRecommend(Request $request)
    {
        $recommend = null;
        if($request->method() != 'POST' || !$request->has('id') || ($recommend = RecommendPrize::find($request->id)) == null) {
            return back()->withErrors(['推荐有礼不存在！']);
        }

        try {
             DB::transaction(function() use ($recommend) {
                // throw new Exception("Connect failed!");
                $recommend->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function sendMoney(Request $request)
    {
        $money = Food::getSendMoney();

        return view('admin.backend.manages.send-money', compact('money'));
    }

    public function updateSendMoney(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }

        $item = DB::table('base_dictionary_option')->where([
            ['dictionary_table_code', 'send'],
            ['dictionary_code', 'money'],
            ['key', 'money']
        ])->first();

        if($item == null) {
            DB::table('base_dictionary_option')->insert([
                'dictionary_table_code' => 'send',
                'dictionary_code' => 'money',
                'key' => 'money',
                'value' => $request->money,
                'name' => $request->money,
            ]); 
        } else {
            DB::table('base_dictionary_option')->where([
                ['dictionary_table_code', 'send'],
                ['dictionary_code', 'money'],
                ['key', 'money']
            ])->update([
                'value' => $request->money,
                'name' => $request->money
            ]);
        }
        return back();
    }

}
