<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Cost;
use App\Models\OrderSend;
use App\Models\SendChange;
use Excel;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
    	$month = Carbon::now()->month;
    	$start_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->toDateTimeString();
    	$end_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addMonth()->toDateTimeString();
        $start_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->toDateString();
        $end_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addMonth()->toDateString();
    	$total_money = Order::where([
    		['created_at', '>=', $start_time],
    		['created_at', '<', $end_time]
    	])->get()->sum('money');

    	$total_money_except_not_pay = Order::where([
    		['created_at', '>=', $start_time],
    		['created_at', '<', $end_time],
    		['pay_status', 1]
    	])->get()->sum('money');

    	$total_send = OrderSend::where([
    		['time', '>=', $start_date],
    		['time', '<', $end_date],
    		['status', 2]
    	])->get()->sum('num');

    	$total_order = Order::where([
    		['created_at', '>=', $start_time],
    		['created_at', '<', $end_time],
            ['pay_status', 1]
    	])->get()->count();

    	$total_more_days_order = Order::where([
    		['created_at', '>=', $start_time],
    		['created_at', '<', $end_time],
            ['pay_status', 1],
    		['days', '>', 1]
    	])->get()->count();

    	$continue_buy_rate = 0;
        if($total_order != 0) {
            $continue_buy_rate = round((double)$total_more_days_order / (double)$total_order, 2) * 100;
        }

    	$inventories = Inventory::where([
    		['last_out_time', '>=', $start_date],
    		['last_out_time', '<', $end_date],
    		['type', 1]
    	])->get()->sum('total_money');

    	$costs = Cost::where([
    		['add_date', '>=', $start_date],
    		['add_date', '<', $end_date]
    	])->get();

    	$costs_money = 0;
    	foreach($costs as $cost) {
    		if($cost->time == '一个月') {
    			$costs_money += round($cost->money, 2);
    		} elseif($cost->time == '半年') {
    			$costs_money += round($cost->money / 6, 2);
    		} elseif($cost->time == '一年') {
    			$costs_money += round($cost->money / 12, 2);
    		}
    	}

    	$profit = $total_money_except_not_pay - $inventories - $costs_money;
        $profit_rate = 0;
        if($total_money_except_not_pay != 0) {
            $profit_rate = round((double)$profit / (double)$total_money_except_not_pay, 2) * 100;
        }

        $send_num_array = array();
        $pre_get_money_array = array();
        $real_get_money_array = array();
        $profit_array = array();

        for($i = 0; $i < 4; ++$i) {
            $tmp_start_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addWeeks($i)->toDateTimeString();
            $tmp_end_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addWeeks($i + 1)->toDateTimeString();
            $tmp_start_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addWeeks($i)->toDateString();
            $tmp_end_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addWeeks($i + 1)->toDateString();
            if($i == 3) {
                $tmp_end_time = $end_time;
            }

            $tmp_send_num = OrderSend::where([
                ['time', '>=', $tmp_start_date],
                ['time', '<', $tmp_end_date],
                ['status', 2]
            ])->get()->sum('num');

            array_push($send_num_array, $tmp_send_num);

            $tmp_pre_get_money = Order::where([
                ['created_at', '>=', $tmp_start_time],
                ['created_at', '<', $tmp_end_time]
            ])->get()->sum('money');

            array_push($pre_get_money_array, $tmp_pre_get_money);

            $tmp_real_get_money = Order::where([
                ['created_at', '>=', $tmp_start_time],
                ['created_at', '<', $tmp_end_time],
                ['pay_status', 1]
            ])->get()->sum('money');

            array_push($real_get_money_array, $tmp_real_get_money);

            $tmp_inventories = Inventory::where([
                ['last_out_time', '>=', $tmp_start_date],
                ['last_out_time', '<', $tmp_end_date],
                ['type', 1]
            ])->get()->sum('total_money');

            $tmp_profit = $tmp_real_get_money - $tmp_inventories;
            array_push($profit_array, $tmp_profit);
        }

    	return view('admin.backend.revenues.index', compact('month', 'total_money', 'total_money_except_not_pay', 'total_send', 'continue_buy_rate', 'profit', 'profit_rate', 'send_num_array', 'pre_get_money_array', 'real_get_money_array', 'profit_array', 'inventories', 'costs_money'));
    }

    public function exportSend(Request $request)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $start_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->toDateTimeString();
        $end_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addMonth()->toDateTimeString();

        $orders = Order::where([
            ['created_at', '>=', $start_time],
            ['created_at', '<', $end_time],
            ['pay_status', 1]
        ])->get();

        $items = array();
        $item = array('姓名', '性别', '电话', '商品', '数量', '实际收款', '折扣金额', '备注', '日期');
        array_push($items, $item);
        
        foreach($orders as $order) {
            $item = array();
            array_push($item, $order->real_name);
            $gender = $order->gender == 1 ? '男' : ($order->gender == 2 ? '女' : '未填写');
            array_push($item, $gender);
            array_push($item, $order->tel);
            $food = $order->type == 1 ? '男士餐' : ($request->type == 2 ? '女士餐' : '工作餐');
            array_push($item, $food);
            array_push($item, $order->num);
            array_push($item, $order->money);
            array_push($item, $order->discount_money == null ? 0 : $order->discount_money);
            array_push($item, $order->remarks);
            array_push($item, $order->created_at->toDateString());
            array_push($items, $item);
        }

        $o_items = array();
        $o_item = array('金额/日期', '实际预售', '预售折扣', '实际预售退款', '实际预售运费', '实际预售男士', '实际预售女士', '实际预售工作餐', '实际预售男士退款', '实际预售女士退款', '实际预售工作餐退款');
        array_push($o_items, $o_item);
        $flag = 0;
        $today = Carbon::now()->day;
        for($i = 0; $i <= 30; ++$i) {
            $tmp_begin_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addDays($i)->toDateTimeString();
            $tmp_end_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addDays($i + 1)->toDateTimeString();
            $tmp_day = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addDays($i)->day;
            if($today == $tmp_day) {
                $flag = 1;
            }
            $o_item = array();
            array_push($o_item, '' . $tmp_day . '日');
            $tmp_orders = Order::where([
                ['created_at', '>=', $tmp_begin_time],
                ['created_at', '<', $tmp_end_time],
                ['pay_status', 1]
            ])->get();
            $tmp = $tmp_orders->sum('money');
            array_push($o_item, $tmp);
            $tmp = $tmp_orders->sum('discount_money');
            array_push($o_item, $tmp);

            $tmp_changes = SendChange::where([
                ['created_at', '>=', $tmp_begin_time],
                ['created_at', '<', $tmp_end_time],
                ['type', 6]
            ])->orWhere([
                ['created_at', '>=', $tmp_begin_time],
                ['created_at', '<', $tmp_end_time],
                ['type', 7]
            ])->get();

            $tmp = $tmp_orders->sum('back_money');
            array_push($o_item, $tmp);

            $tmp = $tmp_orders->where('type', 1)->sum('money');
            array_push($o_item, $tmp);
            $tmp = $tmp_orders->where('type', 2)->sum('money');
            array_push($o_item, $tmp);
            $tmp = $tmp_orders->where('type', 3)->sum('money');
            array_push($o_item, $tmp);

            $tmp = $tmp_orders->where('food_type', 1)->sum('back_money');
            array_push($o_item, $tmp);
            $tmp = $tmp_orders->where('food_type', 1)->sum('back_money');
            array_push($o_item, $tmp);
            $tmp = $tmp_orders->where('food_type', 2)->sum('back_money');
            array_push($o_item, $tmp);
            $tmp = $tmp_orders->where('food_type', 3)->sum('back_money');
            array_push($o_item, $tmp);

            array_push($o_items, $o_item);
            if($flag == 1) break;
        }

        $title = $year . '年' . $month . '明细统计';

        $a_items = array();
        for($i = 0; $i < count($o_items[0]); ++$i) {
            $a_items[$i] = array();
        }
        for($i = 0; $i < count($o_items); ++$i) {
            for($j = 0; $j < count($o_items[$i]); ++$j) {
                $a_items[$j][$i] = $o_items[$i][$j];
            }
        }
        Excel::create($title, function($excel) use($items, $a_items) {
            $excel->sheet('收入明细', function($sheet) use($a_items) {
                $sheet->fromArray($a_items, null, 'A1', true, false);

                $row = count($a_items);
                $col = count($a_items[0]);

                $left_top = $sheet->getCellByColumnAndRow(0, 1)->getCoordinate();
                $right_bottom = $sheet->getCellByColumnAndRow($col - 1, $row)->getCoordinate();
                $range = $left_top . ":" . $right_bottom;
                $sheet->cells($range, function($cells) {
                    $cells->setAlignment('center')->setValignment('center');
                });

                for($i = 0; $i < $col; ++$i) {
                    for($j = 0; $j < $row; ++$j) {
                        $left_top = $sheet->getCellByColumnAndRow($i, $j + 1)->getCoordinate();
                        $sheet->getStyle($left_top)->getAlignment()->setWrapText(true);
                        $sheet->setWidth($sheet->getColumnDimensionByColumn($j)->getColumnIndex(), 14);
                    }
                }
                // $sheet->setOrientation('landscape');

                // $sheet->setAutoSize(true);
            });

            $excel->sheet('配送明细', function($sheet) use($items) {
                $sheet->fromArray($items, null, 'A1', true, false);

                $row = count($items);
                $col = count($items[0]);

                $left_top = $sheet->getCellByColumnAndRow(0, 1)->getCoordinate();
                $right_bottom = $sheet->getCellByColumnAndRow($col - 1, $row)->getCoordinate();
                $range = $left_top . ":" . $right_bottom;
                $sheet->cells($range, function($cells) {
                    $cells->setAlignment('center')->setValignment('center');
                });

                for($i = 0; $i < $col; ++$i) {
                    for($j = 0; $j < $row; ++$j) {
                        $left_top = $sheet->getCellByColumnAndRow($i, $j + 1)->getCoordinate();
                        $sheet->getStyle($left_top)->getAlignment()->setWrapText(true);
                        $sheet->setWidth($sheet->getColumnDimensionByColumn($j)->getColumnIndex(), 14);
                    }
                }

                // $sheet->setAutoSize(true);
            });
        })->export('xls');
    }
}
