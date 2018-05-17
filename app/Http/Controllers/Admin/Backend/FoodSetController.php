<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\FoodSet;
use DB;

class FoodSetController extends Controller
{
    public function index(Request $request)
    {
    	$food_sets = FoodSet::orderBy('sort')->get();

    	return view('admin.backend.food-sets.index', compact('food_sets'));
    }

	public function edit(Request $request)
    {
    	$food_set = null;
    	if(! $request->has('id') || ($food_set = FoodSet::find($request->id)) == null) {
    		return back()->withErrors(['套餐不存在！']);
    	}

    	return view('admin.backend.food-sets.edit', compact('food_set'));
    }

	public function update(Request $request)
    {
    	$food_set = null;
        if($request->method() != 'POST' || ! $request->has('id') || ($food_set = FoodSet::find($request->id)) == null) {
            return back()->withErrors(['套餐不存在！']);
        }

        // $food_set->type = $request->type;
        // $food_set->kind = $request->kind;
        $food_set->money = $request->money;
        $food_set->sort = $request->sort;
        // $food_set->status = $request->status;
        if(((int) $request->is_change_poster) == 1) {
            $food_set->poster = $request->poster;
        }

        if(! $food_set->saveOrFail()) {
            return back()->withErrors(['数据库保存失败！']);
        }

        return redirect(U('Backend/FoodSet/index'));
    }

	public function changeStatus(Request $request)
    {
        $food_set = null;
        if($request->method() != 'POST' || !$request->has('id') || ($food_set = FoodSet::find($request->id)) == null) {
            return back()->withErrors(['未找到要改变状态的套餐！']);
        }

        $food_set->status = $request->status;
        if(! $food_set->saveOrFail()) {
            return back()->withErrors(['数据库保存失败！']);
        }

        return redirect(U('Backend/FoodSet/index'));
    }
}
