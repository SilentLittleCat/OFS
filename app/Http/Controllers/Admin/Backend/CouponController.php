<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use App\Models\Coupon;
use Carbon\Carcon;
use DB, Exception;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = null;
        $keyword = null;

        if($request->has('keyword') && $request->input('keyword') != '') {
            $keyword = '%' . $request->input('keyword') . '%';
            $coupons = Coupon::where('id', 'like', $keyword)->orWhere('name', 'like', $keyword)->orderBy('created_at', 'desc')->paginate(env('EACH_PAGE_NUM'));
        } else {
            $coupons = Coupon::orderBy('created_at', 'desc')->paginate(env('EACH_PAGE_NUM'));
        }
        $keyword = $request->keyword;
        return view('admin.backend.coupons.index', compact('coupons', 'keyword'));
    }

    public function userIndex(Request $request)
    {
    	$user = null;
        $keyword = null;

    	if(! $request->has('id') || ($user = User::find($request->input('id'))) == null) {
    		return back();
    	}

    	$coupons = $user->coupons();
        if($request->has('keyword') && $request->input('keyword') != '') {
            $keyword = '%' . $request->input('keyword') . '%';
            $coupons = $coupons->where('coupon_id', 'like', $keyword)->orWhere('name', 'like', $keyword);
            $keyword = $request->input('keyword');
        } 
        if($request->has('sort_field')) {
            $coupons = $coupons->orderBy($request->sort_field, $request->sort_field_by);
        }

        $select_coupons = Coupon::where('status', 1)->get();

        foreach($select_coupons as $coupon) {
            if($coupon->type == 0) {
                $coupon->text = $coupon->name . '（通用范围）';
            } else if($coupon->type == 1) {
                $coupon->text = $coupon->name . '（男士餐）';
            } else if($coupon->type == 2) {
                $coupon->text = $coupon->name . '（女士餐）';
            } else if($coupon->type == 3) {
                $coupon->text = $coupon->name . '（工作餐）';
            } else {
                $coupon->text = $coupon->name;
            }
        }

        $coupons = $coupons->paginate(env('EACH_PAGE_NUM'));
    	return view('admin.backend.coupons.user-index', compact('coupons', 'user', 'keyword', 'select_coupons'));
    }

    public function create(Request $request)
    {
        return view('admin.backend.coupons.create');
    }

    public function edit(Request $request)
    {
    	$coupon = null;
    	if(! $request->has('id') || ($coupon = Coupon::find($request->input('id'))) == null) {
    		return back();
    	}

    	return view('admin.backend.coupons.edit', compact('coupon'));
    }

    public function update(Request $request)
    {
        $coupon = null;
        if($request->method() != 'POST' || !$request->has('id') || ($coupon = Coupon::find($request->input('id'))) == null) {
            return back();
        }

        $coupon->name = $request->name;
        $coupon->type = $request->type;
        $coupon->condition = $request->condition;
        $coupon->money = $request->money;
        $coupon->status = $request->status;
        $coupon->total = $request->total;
        $coupon->description = $request->description;
        $coupon->begin_time = $request->begin_time;
        $coupon->end_time = $request->end_time;

        if(! $coupon->saveOrFail()) {
            return back()->withErrors(['优惠券修改失败！']);
        }

        return redirect(U('Backend/Coupon/index'));
    }

    public function store(Request $request)
    {
        if($request->method() != 'POST') {
            return back();
        }

        $coupon = new Coupon;
        $coupon->name = $request->name;
        $coupon->type = $request->type;
        $coupon->condition = $request->condition;
        $coupon->money = $request->money;
        $coupon->status = $request->status;
        $coupon->total = $request->total;
        $coupon->description = $request->description;
        $coupon->begin_time = $request->begin_time;
        $coupon->end_time = $request->end_time;

        if(! $coupon->saveOrFail()) {
            return back()->withErrors(['优惠券创建失败！']);
        }

        return redirect(U('Backend/Coupon/index'));
    }

    public function userDelete(Request $request)
    {
        if($request->method() != 'POST' || !$request->has('id')) {
            return back();
        }

        DB::table('coupon_user')->where('id', $request->input('id'))->delete();
        return redirect(U('Backend/Coupon/userIndex', ['id' => $request->input('user_id')]));
    }

    public function delete(Request $request)
    {
        $coupon = null;
        if($request->method() != 'POST' || !$request->has('id') || ($coupon = Coupon::find($request->input('id'))) == null) {
            return back();
        }

        try {
             DB::transaction(function() use ($coupon) {
                DB::table('coupon_user')->where('coupon_id', $coupon->id)->delete();
                DB::table('coupons')->where('id', $coupon->id)->delete();
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }

        return redirect(U('Backend/Coupon/index'));
    }

    public function addForUser(Request $request)
    {
        $user = null;
        $coupon = null;
        if($request->method() != 'POST' || !$request->has('user_id') || ($user = User::find($request->user_id)) == null || !$request->has('coupon_id') || ($coupon = Coupon::find($request->coupon_id)) == null) {
            return back()->withErrors(['找不到用户或优惠券！']);
        }

        if($coupon->use_total >= $coupon->total) {
            $coupon->status = 0;
            $coupon->save();
            return back()->withErrors(['优惠券已发放完毕！']);
        }

        $coupon->use_total += 1;
        if($coupon->use_total >= $coupon->total) {
            $coupon->status = 0;
        }
        $coupon->save();

        DB::table('coupon_user')->insert([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'status' => 0
        ]);

        return back();
    }
}
