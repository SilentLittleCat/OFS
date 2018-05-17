<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\Food;

class FoodController extends Controller
{
    public function index(Request $request)
    {
    	$foods = Food::all();

    	return view('admin.backend.foods.index', compact('foods'));
    }

    public function edit(Request $request)
    {
    	$food = null;
    	if(! $request->has('id') || ($food = Food::find($request->id)) == null) {
    		return back()->withErrors(['商品不存在！']);
    	}

    	return view('admin.backend.foods.edit', compact('food'));
    }

    public function update(Request $request)
    {
    	$food = null;
        if($request->method() != 'POST' || ! $request->has('id') || ($food = Food::find($request->id)) == null) {
            return back()->withErrors(['商品不存在！']);
        }

        $food->money = $request->money;
        if($food->type == 3) {
            $food->a_min = $request->a_min;
            $food->a_max = $request->a_max;
            $food->a_price = $request->a_price;
            $food->b_min = $request->b_min;
            $food->b_price = $request->b_price;
        }
        if(((int) $request->is_change_poster) == 1) {
            $food->poster = $request->poster;
        }
        $food->info = $request->info;

        if(! $food->saveOrFail()) {
            return back()->withErrors(['数据库保存失败！']);
        }

        return redirect(U('Backend/Food/index'));
    }

    public function changeStatus(Request $request)
    {
        $food = null;
        if($request->method() != 'POST' || !$request->has('id') || ($food = Food::find($request->id)) == null) {
            return back()->withErrors(['未找到要改变状态的餐类！']);
        }

        $food->status = $request->status;
        if(! $food->saveOrFail()) {
            return back()->withErrors(['数据库保存失败！']);
        }

        return redirect(U('Backend/Food/index'));
    }
}
