<?php

namespace App\Http\Controllers\Admin\Backend;

use App\Models\SendRange;
use App\Models\County;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SendRangeController extends Controller
{
    public function index(Request $request)
    {
    	$records = SendRange::orderBy('updated_at', 'desc')->get();
        $cities = Area::where('level', 2)->get();
        $tmp_cities = clone $cities;
    	$default_city = $tmp_cities->where('name', '成都市')->first();
        $counties = Area::where('pid', $default_city->id)->get();
    	return view('admin.backend.send-range.index', compact('records', 'cities', 'default_city', 'counties'));
    }

    public function store(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back()->withErrors(['发生错误！']);
    	}

        $item = SendRange::where([
            'city' => $request->city,
            'county' => $request->county
        ])->get();
        if($item->count() > 0) {
            return back()->withErrors(['该配送区域已存在！']);
        }

    	$range = new SendRange;
        $range->city = $request->city;
    	$range->county = $request->county;

    	if(! $range->saveOrFail()) {
    		return back()->withErrors(['数据库储存失败！']);
    	}

    	return back();
    }

    public function update(Request $request)
    {
    	$range = null;

    	if($request->method() != 'POST' || !$request->has('id') || ($range = SendRange::find($request->id)) == null) {
    		return back()->withErrors(['发生错误或者未找到要更新的区域！']);
    	}

    	$range->county = $request->county;

    	if(! $range->saveOrFail()) {
    		return back()->withErrors(['数据库储存失败！']);
    	}

    	return back();
    }

    public function destroy(Request $request)
    {
    	$range = null;
    	if($request->method() != 'POST' || !$request->has('id') || (($range = SendRange::find($request->id)) == null)) {
    		return back()->withErrors(['发生错误或者未找到要更新的区域！']);
    	}

    	$range->delete();

    	return back();
    }

    public function queryCounties(Request $request)
    {
        if($request->method() != 'POST' || !$request->has('city_id')) {
            return back();
        }
        $counties = Area::where('pid', $request->city_id)->get()->pluck('name')->toArray();

        return response()->json($counties);
    }

    public function queryCountiesFromName(Request $request)
    {
        if($request->method() != 'POST' || !$request->has('city')) {
            return back();
        }

        $counties = SendRange::where('city', $request->city)->get()->pluck('county')->toArray();
        return response()->json($counties);
    }
}
