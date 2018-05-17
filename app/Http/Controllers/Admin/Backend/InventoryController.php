<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\Inventory;
use App\Models\Scene;
use App\Models\BuyClass;
use App\Models\WarnLine;
use App\Models\CostRange;
use DB;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $inventories = Inventory::where('type', 2)->orderBy('updated_at', 'desc');
        $filter_scene = null;
        $filter_class = null;
        $filter_begin_time = null;
        $filter_end_time = null;
        $search_name = null;

        if($request->has('scene') && $request->scene != 'all') {
            $filter_scene = $request->scene;
            $inventories = $inventories->where('scene', $request->scene);
        }

        if($request->has('buy_class') && $request->buy_class != 'all') {
            $filter_class = $request->buy_class;
            $inventories = $inventories->where('two_level', $request->buy_class)->orWhere('three_level', $request->buy_class);
        }

        if($request->has('begin_time')) {
            $filter_begin_time = $request->begin_time;
            $inventories = $inventories->where('last_enter_time', '>=', $request->begin_time);
        }

        if($request->has('quick_time')) {
            $date = null;
            if($request->quick_time == 'one_week') {
                $date = Carbon::today()->subWeek();
            } else if($request->quick_time == 'one_month') {
                $date = Carbon::today()->subMonth();
            } else if($request->quick_time == 'one_year') {
                $date = Carbon::today()->subYear();
            }
            $inventories = $inventories->where('last_enter_time', '>=', $date->toDateString());
        }

        if($request->has('end_time')) {
            $filter_end_time = $request->end_time;
            $inventories = $inventories->where('last_enter_time', '<=', $request->end_time);
        }

        if($request->has('name')) {
            $search_name = $request->name;
            $inventories = $inventories->where('name', $request->name);
        }

        $scenes = Scene::orderBy('sort')->get();
        $two_levels = BuyClass::where('sort', 0)->orderBy('sort')->get();
        $three_levels = BuyClass::where('sort', '<>', 0)->orderBy('sort')->get();
        $buy_classes = BuyClass::orderBy('sort')->get();

        // $items = WarnLine::where('status', 1)->get();

        // foreach($items as $item) {
        //     $num = Inventory::where([
        //         ['name', $item->name],
        //         ['type', 0]
        //     ])->get()->sum('num') - Inventory::where([
        //         ['name', $item->name],
        //         ['type', 1]
        //     ])->get()->sum('num');
        //     $warn_line = WarnLine::where([
        //         ['status', 1],
        //         ['name', $item->name],
        //         ['line', '>=', $num]
        //     ])->first();
        //     if($item->line >= $num) {
        //         $item->warn = 1;
        //     } else {
        //         $item->warn = 0;
        //     }
        //     $item->num = $num;
        // }

        $inventories = $inventories->paginate(env('EACH_PAGE_NUM'));

        return view('admin.backend.inventories.index', compact('inventories', 'scenes', 'buy_classes', 'filter_scene', 'filter_class', 'filter_begin_time', 'filter_end_time', 'search_name', 'two_levels', 'three_levels'));
    }

    public function outFromIndex(Request $request)
    {
        $inventory = null;
        if($request->method() != 'POST' || !$request->has('id') || ($inventory = Inventory::find($request->id)) == null) {
            return back()->withErrors(['找不到要出库的记录！']);
        }

        Inventory::create([
            'scene' => $inventory->scene,
            'two_level' => $inventory->two_level,
            'three_level' => $inventory->three_level,
            'type' => 1,
            'is_direct_cost' => $inventory->is_direct_cost,
            'name' => $inventory->name,
            'username' => $request->username,
            'standard' => $inventory->standard,
            'unit' => $inventory->unit,
            'num' => $request->num,
            'price' => $inventory->price,
            'total_money' => ($inventory->price * $request->num),
            'old_years' => $inventory->old_years,
            'old_rate' => $inventory->old_rate,
            'old_cost' => $inventory->old_cost,
            'last_out_time' => $request->time,
            'remarks' => $inventory->remarks
        ]);

        $inventory->num -= $request->num;
        $warn_line = WarnLine::where([
            ['status', '=', '1'],
            ['name', '=', $inventory->name],
            ['line', '>', $inventory->num]
        ])->first();
        if($warn_line != null) {
            $inventory->status = $warn_line->info;
        }
        $inventory->last_out_time = $request->time;
        if(! $inventory->saveOrFail()) {
            return back()->withErrors(['更新失败！']);
        }

        return back();
    }

    public function enterIndex(Request $request)
    {
    	$inventories = Inventory::where('type', 0)->orderBy('updated_at', 'desc');
        $filter_scene = null;
        $filter_class = null;
        $filter_begin_time = null;
        $filter_end_time = null;
        $search_name = null;

    	if($request->has('scene') && $request->scene != 'all') {
            $filter_scene = $request->scene;
    		$inventories = $inventories->where('scene', $request->scene);
    	}

    	if($request->has('buy_class') && $request->buy_class != 'all') {
            $filter_class = $request->buy_class;
    		$inventories = $inventories->where('two_level', $request->buy_class)->orWhere('three_level', $request->buy_class);
    	}

    	if($request->has('begin_time')) {
            $filter_begin_time = $request->begin_time;
    		$inventories = $inventories->where('last_enter_time', '>=', $request->begin_time);
    	}

        if($request->has('quick_time')) {
            $date = null;
            if($request->quick_time == 'one_week') {
                $date = Carbon::today()->subWeek();
            } else if($request->quick_time == 'one_month') {
                $date = Carbon::today()->subMonth();
            } else if($request->quick_time == 'one_year') {
                $date = Carbon::today()->subYear();
            }
            $inventories = $inventories->where('last_enter_time', '>=', $date->toDateString());
        }

        if($request->has('end_time')) {
            $filter_end_time = $request->end_time;
            $inventories = $inventories->where('last_enter_time', '<=', $request->end_time);
        }

        if($request->has('name')) {
            $search_name = $request->name;
            $inventories = $inventories->where('name', $request->name);
        }

        $scenes = Scene::orderBy('sort')->get();
        $buy_classes = BuyClass::orderBy('sort')->get();

        $items = WarnLine::where('status', 1)->get();

        foreach($items as $item) {
            $num = Inventory::where([
                ['name', $item->name],
                ['type', 0]
            ])->get()->sum('num') - Inventory::where([
                ['name', $item->name],
                ['type', 1]
            ])->get()->sum('num');
            $warn_line = WarnLine::where([
                ['status', 1],
                ['name', $item->name],
                ['line', '>=', $num]
            ])->first();
            if($item->line >= $num) {
                $item->warn = 1;
            } else {
                $item->warn = 0;
            }
            $item->num = $num;
        }

    	$inventories = $inventories->paginate(env('EACH_PAGE_NUM'));

    	return view('admin.backend.inventories.enter-index', compact('inventories', 'scenes', 'buy_classes', 'filter_scene', 'filter_class', 'filter_begin_time', 'filter_end_time', 'search_name', 'items'));
    }

    public function outIndex(Request $request)
    {
        $inventories = Inventory::where('type', 1)->orderBy('updated_at', 'desc');
        $filter_scene = null;
        $filter_class = null;
        $filter_begin_time = null;
        $filter_end_time = null;
        $search_name = null;

        if($request->has('scene') && $request->scene != 'all') {
            $filter_scene = $request->scene;
            $inventories = $inventories->where('scene', $request->scene);
        }

        if($request->has('buy_class') && $request->buy_class != 'all') {
            $filter_class = $request->buy_class;
            $inventories = $inventories->where('two_level', $request->buy_class)->orWhere('three_level', $request->buy_class);
        }

        if($request->has('begin_time')) {
            $filter_begin_time = $request->begin_time;
            $inventories = $inventories->where('last_out_time', '>=', $request->begin_time);
        }

        if($request->has('quick_time')) {
            $date = null;
            if($request->quick_time == 'one_week') {
                $date = Carbon::today()->subWeek();
            } else if($request->quick_time == 'one_month') {
                $date = Carbon::today()->subMonth();
            } else if($request->quick_time == 'one_year') {
                $date = Carbon::today()->subYear();
            }

            $inventories = $inventories->where('last_out_time', '>=', $date->toDateString());
        }

        if($request->has('end_time')) {
            $filter_end_time = $request->end_time;
            $inventories = $inventories->where('last_out_time', '<=', $request->end_time);
        }

        if($request->has('name')) {
            $search_name = $request->name;
            $inventories = $inventories->where('name', $request->name);
        }

        $scenes = Scene::orderBy('sort')->get();
        $buy_classes = BuyClass::where([
            ['sort', 0],
            ['status', 1]
        ])->orderBy('sort')->get();
        $day = Carbon::now();
        while($day->dayOfWeek != 0) {
            $day = $day->addDay();
        }
        $week_end = $day->toDateString();
        $week_begin = $day->subWeek()->addDay()->toDateString();
        $inventories_tmp = Inventory::where([
            ['type', 1],
            ['last_out_time', '>=', $week_begin],
            ['last_out_time', '<=', $week_end]
        ])->orderBy('updated_at', 'desc'); 
        $tmp = clone $inventories_tmp;
        $total_money = $tmp->get()->sum('total_money');
        // dd($inventories_tmp->get(), $total_money);
        foreach($buy_classes as $buy_class) {
            $tmp = clone $inventories_tmp;
            $num = 0;
            if($buy_class->sort == 0) {
                $num = $tmp->where('two_level', $buy_class->name)->get()->sum('total_money');
            } else {
                $num = $tmp->where('three_level', $buy_class->name)->get()->sum('total_money');
            }
            $range = 0;
            if($total_money != 0) {
                $range = ($num / $total_money) * 100;
            }

            $buy_class->total_money = $num;
            $cost_range = CostRange::where([
                ['name', $buy_class->name],
                ['range_min', '>', $range]
            ])->orWhere([
                ['name', $buy_class->name],
                ['range_max', '<', $range]
            ])->first();
            $buy_class->cost_range = $cost_range;
            // if($buy_class->name == '包材消耗') {
            //     dd($range, )
            // }
        }

        // $datas = collect([]);
        // $tmp = $inventories->get();
        // $total_money = $tmp->sum('total_money');
        // foreach($buy_classes as $buy_class) {
            
        // }

        $inventories = $inventories->paginate(env('EACH_PAGE_NUM'));

        return view('admin.backend.inventories.out-index', compact('inventories', 'scenes', 'buy_classes', 'filter_scene', 'filter_class', 'filter_begin_time', 'filter_end_time', 'search_name', 'total_money'));
    }

    public function createEnter(Request $request)
    {
        $scenes = Scene::orderBy('sort')->get();
        $two_levels = BuyClass::where('sort', 0)->orderBy('sort')->get();
        $three_levels = BuyClass::where('sort', '<>', 0)->orderBy('sort')->get();
        $names = Inventory::orderBy('updated_at', 'desc')->get()->pluck('name')->unique()->implode(',');
        // dd($names);

    	return view('admin.backend.inventories.enter-create', compact('scenes', 'two_levels', 'three_levels', 'names'));
    }

    public function createOut(Request $request)
    {
        $scenes = Scene::orderBy('sort')->get();
        $two_levels = BuyClass::where('sort', 0)->orderBy('sort')->get();
        $three_levels = BuyClass::where('sort', '<>', 0)->orderBy('sort')->get();
        $names = Inventory::orderBy('updated_at', 'desc')->get()->pluck('name')->unique()->implode(',');

        return view('admin.backend.inventories.out-create', compact('scenes', 'two_levels', 'three_levels', 'names'));
    }

    public function enterSaveAll(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back();
    	}

        $time = $request->input('time');
        $username = $request->input('username');
        $items = $request->input('data');

        foreach ($items as $item) {
            $old_years = null;
            $old_rate = null;
            $old_cost = null;
            $is_direct_cost = 0;
            $tmp = DB::table('buy_class')->where('name', $item['three_level'])->first();
            if($tmp != null) {
                $is_direct_cost = $tmp->is_direct_cost;
            }
            if($item['old_years'] != 'null') {
                $old_years = $item['old_years'];
                $old_rate = dict()->get('inventory', 'old_rate', $old_years);
                $old_cost = round((double)$item['total_money'] * (double)$old_rate, 2);
            }

            $warn_line = WarnLine::where([
                ['status', '=', '1'],
                ['name', '=', $item['name']],
                ['line', '>', $item['num']]
            ])->first();
            $status = '';
            if($warn_line != null) {
                $status = $warn_line->info;
            }

            // 入库信息
            Inventory::create([
                'scene' => $item['scene'],
                'two_level' => $item['two_level'],
                'three_level' => $item['three_level'],
                'is_in' => $item['is_in'],
                'type' => 0,
                'is_direct_cost' => $is_direct_cost,
                'name' => $item['name'],
                'username' => $username,
                'standard' => $item['standard'],
                'unit' => $item['unit'],
                'num' => $item['num'],
                'price' => $item['price'],
                'total_money' => $item['total_money'],
                'status' => $status,
                'old_years' => $old_years,
                'old_rate' => '' . ((double)$old_rate * 100) . '%',
                'old_cost' => $old_cost,
                'last_enter_time' => $time,
                'remarks' => $item['remarks']
            ]);

            if($item['is_in'] == 1) {
                // 库存管理
                $inventory = Inventory::where([
                    ['type', 2],
                    ['name', $item['name']]
                ])->first();
                if($inventory == null) {
                    Inventory::create([
                        'scene' => $item['scene'],
                        'two_level' => $item['two_level'],
                        'three_level' => $item['three_level'],
                        'is_in' => $item['is_in'],
                        'type' => 2,
                        'is_direct_cost' => $is_direct_cost,
                        'name' => $item['name'],
                        'username' => $username,
                        'standard' => $item['standard'],
                        'unit' => $item['unit'],
                        'num' => $item['num'],
                        'price' => $item['price'],
                        'total_money' => $item['total_money'],
                        'status' => $status,
                        'old_years' => $old_years,
                        'old_rate' => '' . ((double)$old_rate * 100) . '%',
                        'old_cost' => $old_cost,
                        'last_enter_time' => $time,
                        'remarks' => $item['remarks']
                    ]);
                } else {
                    $inventory->num = $inventory->num + $item['num'];
                    $inventory->total_money = round($inventory->num * $inventory->price, 2);
                    $inventory->save();
                }
            }
        }

        return redirect(U('Backend/Inventory/enterIndex'));
    }

    public function outSaveAll(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }

        $time = $request->input('time');
        $username = $request->input('username');
        $items = $request->input('data');

        foreach ($items as $item) {
            $old_years = null;
            $old_rate = null;
            $old_cost = null;
            $is_direct_cost = DB::table('buy_class')->where('name', $item['three_level'])->first()->is_direct_cost;
            if($item['old_years'] != 'null') {
                $old_years = $item['old_years'];
                $old_rate = dict()->get('inventory', 'old_rate', $old_years);
                $old_cost = round((double)$item['total_money'] * (double)$old_rate, 2);
            }

            Inventory::create([
                'scene' => $item['scene'],
                'two_level' => $item['two_level'],
                'three_level' => $item['three_level'],
                'is_in' => $item['is_in'],
                'type' => 1,
                'is_direct_cost' => $is_direct_cost,
                'name' => $item['name'],
                'username' => $username,
                'standard' => $item['standard'],
                'unit' => $item['unit'],
                'num' => $item['num'],
                'price' => $item['price'],
                'total_money' => $item['total_money'],
                'status' => $status,
                'old_years' => $old_years,
                'old_rate' => '' . ((double)$old_rate * 100) . '%',
                'old_cost' => $old_cost,
                'last_enter_time' => $time,
                'remarks' => $item['remarks']
            ]);
        }

        return redirect(U('Backend/Inventory/outIndex'));
    }

    public function updateEnter(Request $request)
    {
        $inventory = null;
        if($request->method() != 'POST' || !$request->has('id') || ($inventory = Inventory::where('type', 0)->find($request->input('id'))) == null) {
            return back()->withErrors(['您要更新的入库记录不存在！']);
        }

        $dataTime = Carbon::now();
        try {
             DB::transaction(function() use ($request, $dataTime, $inventory) {
                // throw new Exception("Connect failed!");

                $old_years = null;
                $old_rate = null;
                $old_cost = null;
                $is_direct_cost = DB::table('buy_class')->where('name', $request->three_level)->first()->is_direct_cost;
                if($request->old_years != 'null') {
                    $old_years = $request->old_years;
                    $old_rate = dict()->get('inventory', 'old_rate', $old_years);
                    $old_cost = round((double)$request->total_money * (double)$old_rate, 2);
                }

                $inventory->scene = $request->scene;
                $inventory->two_level = $request->two_level;
                $inventory->three_level = $request->three_level;
                $inventory->is_in = $request->is_in;
                $inventory->is_direct_cost = $is_direct_cost;
                $inventory->name = $request->name;
                $inventory->username = $request->username;
                $inventory->standard = $request->standard;
                $inventory->unit = $request->unit;
                $inventory->num = $request->num;
                $inventory->price = $request->price;
                $inventory->total_money = $request->total_money;
                $inventory->old_years = $old_years;
                $inventory->old_rate = $old_rate;
                $inventory->old_cost = $old_cost;
                $inventory->last_enter_time = $request->time;
                $inventory->remarks = $request->remarks;
                $inventory->created_at = $dataTime;
                $inventory->updated_at = $dataTime;

                $inventory->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return redirect(U('Backend/Inventory/enterIndex'));
    }

    public function updateOut(Request $request)
    {
        $inventory = null;
        if($request->method() != 'POST' || !$request->has('id') || ($inventory = Inventory::where('type', 1)->find($request->input('id'))) == null) {
            return back()->withErrors(['您要更新的出库记录不存在！']);
        }

        $dataTime = Carbon::now();
        try {
             DB::transaction(function() use ($request, $dataTime, $inventory) {
                // throw new Exception("Connect failed!");

                $old_years = null;
                $old_rate = null;
                $old_cost = null;
                if($request->old_years != 'null') {
                    $old_years = $request->old_years;
                    $old_rate = dict()->get('inventory', 'old_rate', $old_years);
                    $old_cost = round((double)$request->total_money * (double)$old_rate, 2);
                }

                $inventory->scene = $request->scene;
                $inventory->two_level = $request->two_level;
                $inventory->three_level = $request->three_level;
                $inventory->name = $request->name;
                $inventory->username = $request->usernaem;
                $inventory->standard = $request->standard;
                $inventory->unit = $request->unit;
                $inventory->num = $request->num;
                $inventory->price = $request->price;
                $inventory->total_money = $request->total_money;
                $inventory->old_years = $old_years;
                $inventory->old_rate = $old_rate;
                $inventory->old_cost = $old_cost;
                $inventory->last_out_time = $request->time;
                $inventory->remarks = $request->remarks;
                $inventory->created_at = $dataTime;
                $inventory->updated_at = $dataTime;

                $inventory->save();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return redirect(U('Backend/Inventory/outIndex'));
    }

    public function deleteEnter(Request $request)
    {
    	$inventory = null;
     	if($request->method() != 'POST' || !$request->has('id') || ($inventory = Inventory::where('type', 0)->find($request->input('id'))) == null) {
     		return back()->withErrors(['您要删除的入库记录不存在！']);
     	}

        try {
             DB::transaction(function() use ($inventory) {
                // throw new Exception("Connect failed!");

             	$inventory->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['数据库发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteOut(Request $request)
    {
        $inventory = null;
        if($request->method() != 'POST' || !$request->has('id') || ($inventory = Inventory::where('type', 1)->find($request->input('id'))) == null) {
            return back()->withErrors(['您要删除的出库记录不存在！']);
        }

        try {
             DB::transaction(function() use ($inventory) {
                // throw new Exception("Connect failed!");

                $inventory->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['数据库发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function deleteManage(Request $request)
    {
        $inventory = null;
        if($request->method() != 'POST' || !$request->has('id') || ($inventory = Inventory::where('type', 2)->find($request->input('id'))) == null) {
            return back()->withErrors(['您要删除的库存管理记录不存在！']);
        }

        try {
             DB::transaction(function() use ($inventory) {
                // throw new Exception("Connect failed!");

                $inventory->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['数据库发生错误！', $e->getMessage()]);
        }

        return back();
    }

    public function editEnter(Request $request)
    {
    	$inventory = null;
     	if(!$request->has('id') || ($inventory = Inventory::where('type', 0)->find($request->input('id'))) == null) {
     		return back()->withErrors(['您要编辑的入库记录不存在！']);
     	}

        $scenes = Scene::orderBy('sort')->get();
        $two_levels = BuyClass::where('sort', 0)->orderBy('sort')->get();
        $three_levels = BuyClass::where('sort', '<>', 0)->orderBy('sort')->get();

     	return view('admin.backend.inventories.enter-edit', compact('inventory', 'scenes', 'two_levels', 'three_levels'));
    }

    public function editOut(Request $request)
    {
        $inventory = null;
        if(!$request->has('id') || ($inventory = Inventory::where('type', 1)->find($request->input('id'))) == null) {
            return back()->withErrors(['您要编辑的出库记录不存在！']);
        }

        $scenes = Scene::orderBy('sort')->get();
        $two_levels = BuyClass::where('sort', 0)->orderBy('sort')->get();
        $three_levels = BuyClass::where('sort', '<>', 0)->orderBy('sort')->get();

        return view('admin.backend.inventories.out-edit', compact('inventory', 'scenes', 'two_levels', 'three_levels'));
    }

    public function ajaxName(Request $request)
    {
        $data = array();
        $data['status'] = 0;
        if($request->has('name') && $request->name != null && trim($request->name) != '') {
            $inventory = Inventory::where('name', $request->name)->orderBy('created_at', 'desc')->first();
            if($inventory != null) {
               $data['unit'] = $inventory->unit;
               $data['standard'] = $inventory->standard;
               $data['price'] = $inventory->price;
               $data['status'] = 1;
            }
        }
        return response()->json($data, 200);
    }

    public function getNamesFromThree(Request $request)
    {
        $data = array();
        $data['status'] = 0;
        $data['data'] = array();
        if($request->has('three') && $request->three != null && trim($request->three) != '') {
            $inventories = Inventory::select(['name', 'three_level'])->where('three_level', $request->three)->orderBy('created_at', 'desc')->get()->unique('name');
            foreach($inventories as $inventory) {
                array_push($data['data'], $inventory->name);
            }
            $data['status'] = 1;
        }
        return response()->json($data, 200);
    }
}
