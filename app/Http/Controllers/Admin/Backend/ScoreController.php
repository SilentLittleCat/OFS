<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\BaseAttachmentModel;
use App\Models\Coupon;
use Carbon\Carbon;
use DB, File;

class ScoreController extends Controller
{
    public function index(Request $request)
    {
    	$goods = Good::orderBy('updated_at', 'desc');
        $keyword = null;
        if($request->has('keyword') && $request->keyword != null) {
            $keyword = $request->keyword;
            $goods = $goods->where('name', 'like', '%' . $keyword . '%');
        }

        $goods = $goods->get();

    	return view('admin.backend.goods.index', compact('goods', 'keyword'));
    }

    public function create(Request $request)
    {
        $dateTime = Carbon::now();
        $coupons = Coupon::where([
            ['status', 1],
            ['begin_time', '<=', $dateTime],
            ['end_time', '>=', $dateTime]
        ])->get();
    	return view('admin.backend.goods.create', compact('coupons'));
    }

    public function store(Request $request)
    {
    	if($request->method() != 'POST') {
    		return back();
    	}

    	$good = new Good;
        $good->type = $request->type;
        if($request->type == 1) {
            $request->coupon_id = $request->coupon_id;
        }
    	$good->name = $request->name;
    	$good->score = $request->score;
    	$good->poster = $request->poster;
    	$good->info = $request->info;

    	if(! $good->saveOrFail()) {
    		return back()->withErrors(['商品保存错误！']);
    	}

    	return redirect(U('Backend/Score/index'));
    }

    public function update(Request $request)
    {
        $good = null;
        if($request->method() != 'POST' || !$request->has('id') || ($good = Good::find($request->id)) == null) {
            return back()->withErrors(['更新的商品不存在！']);
        }

        $good->name = $request->name;
        $good->score = $request->score;
        if($request->is_change_img == 1) {
            $good->poster = $request->poster;
        }
        $good->info = $request->info;

        if(! $good->saveOrFail()) {
            return back()->withErrors(['商品保存错误！']);
        }

        return redirect(U('Backend/Score/index'));
    }

    public function edit(Request $request)
    {
        $good = null;
        if(!$request->has('id') || ($good = Good::find($request->id)) == null) {
            return back()->withErrors(['找不到商品！']);
        }
        
        return view('admin.backend.goods.edit', compact('good'));
    }

    public function delete(Request $request)
    {
        $good = null;
        if($request->method() != 'POST' || !$request->has('id') || ($good = Good::find($request->id)) == null) {
            return back()->withErrors(['删除的商品不存在！']);
        }

        $path = null;
        $image = BaseAttachmentModel::where('url', $good->url)->first();
        if($image != null) {
            $path = $image->path;
        }

        try {
             DB::transaction(function() use ($good, $path) {
                // throw new Exception("Connect failed!");
                $good->delete();
                if($path != null) {
                    File::delete($path);
                }
             });
        } catch (\Exception $e) {
            return back()->withErrors(['删除失败！', $e->getMessage()]);
        }

        return back();
    }
}
