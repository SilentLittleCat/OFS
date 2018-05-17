<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cost;
use App\Models\Scene;
use App\Models\CostClass;

class CostController extends Controller
{
    public function index(Request $request)
    {
    	$costs = Cost::where('id', '>', 0);
    	if($request->has('scene') && $request->scene != -1) {
    		$costs = $costs->where('scene', $request->scene);
    	}
    	if($request->has('type') && $request->type != -1) {
    		$costs = $costs->where('type', $request->type);
    	}
    	if($request->has('time') && $request->time != -1) {
    		$costs = $costs->where('time', $request->time);
    	}

        if($request->has('begin_time') && $request->begin_time != null) {
            $costs = $costs->where('add_date', '>=', $request->begin_time);
        }
        if($request->has('end_time') && $request->end_time != null) {
            $costs = $costs->where('add_date', '<=', $request->end_time);
        }

        $cost_classes = CostClass::where('status', 1)->get();
        $total_money = 0;
        $items = $costs->get();

        $total_money = $items->sum('money');
        foreach($cost_classes as $cost_class) {
            $cost_class->total_money = $items->where('type', $cost_class->name)->sum('money');
        }

    	$costs = $costs->orderBy('created_at', 'desc')->paginate(env('EACH_PAGE_NUM'));
    	$scenes = Scene::where('status', 1)->get();
        $info = $request->all();
    	return view('admin.backend.costs.index', compact('costs', 'scenes', 'cost_classes', 'total_money', 'info'));
    }

    public function create(Request $request)
    {
        $scenes = Scene::where('status', 1)->get();
        $cost_classes = CostClass::where('status', 1)->get();
        return view('admin.backend.costs.create', compact('scenes', 'cost_classes'));
    }

    public function storeAll(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }

        $add_date = $request->add_date;
        $username = $request->username;
        $items = $request->data;

        foreach($items as $item) {
            Cost::create([
                'scene' => $item['scene'],
                'type' => $item['type'],
                'time' => $item['time'],
                'money' => $item['money'],
                'remarks' => $item['remarks'],
                'add_date' => $add_date,
                'username' => $username
            ]);
        }

        return redirect(U('Backend/Cost/index'));
    }

    public function update(Request $request)
    {
        $cost = null;
        if($request->method() != 'POST' || !$request->has('id') || ($cost = Cost::find($request->id)) == null) {
            return back()->withErrors(['要更新的运营成本记录不存在！']);
        }

        $cost->scene = $request->scene;
        $cost->type = $request->type;
        $cost->time = $request->time;
        $cost->money = $request->money;
        $cost->username = $request->username;
        $cost->remarks = $request->remarks;
        $cost->add_date = $request->add_date;

        if(! $cost->saveOrFail()) {
            return back()->withErrors(['更新失败！']);
        }

        return redirect(U('Backend/Cost/index'));
    }

    public function delete(Request $request)
    {
        $cost = null;
        if($request->method() != 'POST' || !$request->has('id') || ($cost = Cost::find($request->id)) == null) {
            return back()->withErrors(['删除的运营成本记录不存在！']);
        }

        $cost->delete();
        return back();
    }

    public function edit(Request $request)
    {
        $cost = null;
        if(!$request->has('id') || ($cost = Cost::find($request->id)) == null) {
            return back()->withErrors(['要编辑的运营成本记录不存在！']);
        }

        $scenes = Scene::where('status', 1)->get();
        $cost_classes = CostClass::where('status', 1)->get();
        return view('admin.backend.costs.edit', compact('cost', 'scenes', 'cost_classes'));
    }
}
