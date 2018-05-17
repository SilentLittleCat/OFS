<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SendDate;

class SendDateController extends Controller
{
    public function index(Request $request)
    {
    	$dates = SendDate::orderBy('date', 'desc')->paginate(env('EACH_PAGE_NUM'));
    	return view('admin.backend.send-date.index', compact('dates'));
    }

    public function store(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back()->withErrors(['发生错误！']);
    	}

    	$date = new SendDate;
    	$date->date = $request->date;
    	$date->status = $request->status;

    	if(! $date->saveOrFail()) {
    		return back()->withErrors(['数据库储存失败！']);
    	}

    	return back();
    }

    public function update(Request $request)
    {
    	$date = null;

    	if($request->method() != 'POST' || !$request->has('id') || ($date = SendDate::find($request->id)) == null) {
    		return back()->withErrors(['发生错误或者未找到要更新的日期！']);
    	}

    	$date->date = $request->date;
    	$date->status = $request->status;

    	if(! $date->saveOrFail()) {
    		return back()->withErrors(['数据库储存失败！']);
    	}

    	return back();
    }

    public function destroy(Request $request)
    {
    	$date = null;
    	if($request->method() != 'POST' || !$request->has('id') || (($date = SendDate::find($request->id)) == null)) {
    		return back()->withErrors(['发生错误或者未找到要更新的日期！']);
    	}

    	$date->delete();

    	return back();
    }
}
