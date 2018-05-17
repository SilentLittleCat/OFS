<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderAfterSale;

class BackMoneyController extends Controller
{
    public function index(Request $request)
    {
        $orders = OrderAfterSale::where('id', '>', 0);

        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {

            if($request->has('type') && $request->type != 0) {
                $orders->where('type', $request->type);
            }
            if($request->has('begin_time') && $request->begin_time != null) {
                $orders = $orders->where('time', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $orders = $orders->where('time', '<=', $request->end_time);
            }
            if($request->has('keyword') && $request->keyword != null && trim($request->keyword) != '') {
                $key = trim($request->keyword);
                if(((int)$key) != 0 && strlen($key) == 13) {
                    $orders = $orders->where('id', (int)$key);
                } elseif(((int)$key) != 0) {
                    $orders = $orders->where('tel', $key);
                } else {
                    $orders = $orders->where('name', $key);
                }
            }
        }

        if($request->has('sort_field')) {
            $orders = $orders->orderBy($request->sort_field, $request->sort_field_by);
        } else {
            $orders = $orders->orderBy('id', 'desc');
        }

        $orders = $orders->paginate(env('EACH_PAGE_NUM'));

        $info = $request->all();

        return view('admin.backend.back-money.index', compact('orders', 'info'));
    }

    public function show(Request $request)
    {
        $order = null;
        if(! $request->has('id') || ($order = OrderAfterSale::find($request->id)) == null) {
            return back()->withErrors(['订单不存在！']);
        }

        return view('admin.backend.back-money.show', compact('order'));
    }

    public function changeStatus(Request $request)
    {
    	$order = null;
    	if(! $request->has('id') || $request->method() != 'POST' || ($order = OrderAfterSale::find($request->id)) == null) {
    		return back()->withErrors(['订单不存在！']);
    	}
    	$order->status = $request->status;

    	if(! $order->saveOrFail()) {
    		return back()->withErrors(['数据库储存失败！']);
    	}
    	return back();
    }

    public function changeMoney(Request $request)
    {
    	$order = null;
    	if(! $request->has('id') || $request->method() != 'POST' || ($order = OrderAfterSale::find($request->id)) == null) {
    		return back()->withErrors(['订单不存在！']);
    	}
    	$order->price = $request->money;

    	if(! $order->saveOrFail()) {
    		return back()->withErrors(['数据库储存失败！']);
    	}
    	return back();
    }
}
