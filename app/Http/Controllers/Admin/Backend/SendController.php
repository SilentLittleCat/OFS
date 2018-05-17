<?php

namespace App\Http\Controllers\Admin\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Food;
use App\Models\Score;
use App\Models\OrderSend;
use App\Models\SendChange;
use App\Models\Address;
use App\Models\RecommendPrize;
use DB, Exception, Auth, Excel;
use Carbon\Carbon;

class SendController extends Controller
{
    public function index(Request $request)
    {
        $order_sends = OrderSend::where('id', '>', 0);

        if($request->method() == 'POST' && $request->has('search') && $request->search == true) {

            if($request->has('type') && $request->type != 0) {
                $order_sends->where('type', $request->type);
            }
            if($request->has('begin_time') && $request->begin_time != null) {
                $order_sends = $order_sends->where('time', '>=', $request->begin_time);
            }
            if($request->has('end_time') && $request->end_time != null) {
                $order_sends = $order_sends->where('time', '<=', $request->end_time);
            }
            if($request->has('keyword') && $request->keyword != null && trim($request->keyword) != '') {
                $key = trim($request->keyword);
                if(((int)$key) != 0 && strlen($key) == 13) {
                    $order_sends = $order_sends->where('order_id', (int)$key);
                } elseif(((int)$key) != 0) {
                    $order_sends = $order_sends->where('tel', $key);
                } else {
                    $order_sends = $order_sends->where('name', $key);
                }
            }
        }

        if($request->has('sort_field')) {
            $order_sends = $order_sends->orderBy($request->sort_field, $request->sort_field_by);
        } else {
            $order_sends = $order_sends->orderBy('time', 'desc');
        }

        $order_sends = $order_sends->paginate(env('EACH_PAGE_NUM'));

        $info = $request->all();
        $man_food_price = Food::getFoodPrice(1);
        $woman_food_price = Food::getFoodPrice(2);
        $work_food_price = Food::getFoodPrice(3);

        return view('admin.backend.sends.index', compact('order_sends', 'info', 'man_food_price', 'woman_food_price', 'work_food_price'));
    }

    public function export(Request $request)
    {
        $records = OrderSend::where('status', 0);
        if($request->has('type') && $request->type != 0) {
            $records->where('type', $request->type);
        }
        if($request->has('begin_time') && $request->begin_time != null) {
            $records = $records->where('time', '>=', $request->begin_time);
        }
        if($request->has('end_time') && $request->end_time != null) {
            $records = $records->where('time', '<=', $request->end_time);
        }
        if($request->has('keyword') && $request->keyword != null && trim($request->keyword) != '') {
            $key = trim($request->keyword);
            if(((int)$key) != 0 && strlen($key) == 13) {
                $records = $records->where('order_id', (int)$key);
            } elseif(((int)$key) != 0) {
                $records = $records->where('tel', $key);
            } else {
                $records = $records->where('name', $key);
            }
        }
        $records = $records->orderBy('time', 'desc')->get();

        // $items = array();
        // $item = array('订单号', '收货人', '配送产品', '数量（份数）', '订单金额', '收货地址', '收货人手机');
        // array_push($items, $item);
        // foreach($records as $order) {
        //     $item = array();
        //     array_push($item, sprintf('%013d', $order->id));
        //     array_push($item, $order->name);
        //     $tmp = ($order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐'));
        //     array_push($item, $tmp);
        //     $tmp = $order->num;
        //     array_push($item, $tmp);
        //     $money = round($order->num * $order->price, 2);
        //     array_push($item, $money);
        //     array_push($item, $order->address);
        //     array_push($item, $order->tel);

        //     array_push($items, $item);
        // }

        $items = array();
        $item = array('订单号', '收货人', '收货地址', '收货人手机');
        array_push($items, $item);
        foreach($records as $order) {
            $item = array();
            array_push($item, sprintf('%013d', $order->id));
            array_push($item, $order->name);
            array_push($item, $order->address);
            array_push($item, $order->tel);

            array_push($items, $item);
        }

        $title = '配送订单';
        Excel::create($title, function($excel) use($items) {
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
                        $sheet->setWidth($sheet->getColumnDimensionByColumn($j)->getColumnIndex(), 20);
                    }
                }
            });
        })->export('xls');
    }

    public function updateSend(Request $request)
    {
        $today = Carbon::now()->toDateString();
        OrderSend::where('time', $today)->update(['status' => 1]);
        OrderSend::where('time', '<', $today)->update(['status' => 2]);
        return back();
    }
}
