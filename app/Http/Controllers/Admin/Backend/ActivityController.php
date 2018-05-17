<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\Activity;
use DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
    	$activities = Activity::where('id', '>', 0)->orderBy('updated_at', 'desc');
        $keyword = null;

    	if($request->has('keyword')) {
    		$keyword = '%' . $request->input('keyword') . '%';
    		$activities = $activities->where('id', 'like', $keyword)->orWhere('name', 'like', $keyword);
            $keyword = $request->input('keyword');
    	}

    	$activities = $activities->paginate(env('EACH_PAGE_NUM'));

    	return view('admin.backend.activities.index', compact('activities', 'keyword'));
    }

    public function create(Request $request)
    {
    	return view('admin.backend.activities.create');
    }

    public function store(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back();
    	}

    	$activity = new Activity;
    	$activity->name = $request->name;
    	$activity->type = $request->type;
    	$activity->money = $request->money;
    	$activity->times = $request->times;
    	$activity->status = $request->status;
    	$activity->description = $request->description;
    	$activity->begin_time = $request->begin_time;
    	$activity->end_time = $request->end_time;

    	if(! $activity->saveOrFail()) {
    		return back()->withErrors(['活动保存错误！']);
    	}

    	return redirect(U('Backend/Activity/index'));
    }

    public function edit(Request $request)
    {
        $activity = null;
        if(!$request->has('id') || ($activity = Activity::find($request->id)) == null) {
            return back()->withErrors(['找不到商品！']);
        }
        
        return view('admin.backend.activities.edit', compact('activity'));
    }

    public function update(Request $request)
    {
        $activity = null;
        if($request->method() != 'POST' || !$request->has('id') || ($activity = Activity::find($request->id)) == null) {
            return back()->withErrors(['更新的活动不存在！']);
        }

    	$activity->name = $request->name;
    	$activity->type = $request->type;
    	$activity->money = $request->money;
    	$activity->times = $request->times;
    	$activity->status = $request->status;
    	$activity->description = $request->description;
    	$activity->begin_time = $request->begin_time;
    	$activity->end_time = $request->end_time;

        if(! $activity->saveOrFail()) {
            return back()->withErrors(['更新错误！']);
        }

        return redirect(U('Backend/Activity/index'));
    }

    public function delete(Request $request)
    {
        $activity = null;
        if($request->method() != 'POST' || !$request->has('id') || ($activity = Activity::find($request->id)) == null) {
            return back()->withErrors(['删除的活动不存在！']);
        }
        
        try {
             DB::transaction(function() use ($activity) {
                // throw new Exception("Connect failed!");
                $activity->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['删除活动失败！', $e->getMessage()]);
        }

        return back();
    }
}
