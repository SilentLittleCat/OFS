<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Cost;
use App\Models\Inventory;
use App\Models\Activity;
use App\Models\Good;
use App\Models\ContactUs;
use App\Models\RecommendPrize;
use App\Models\Food;
use App\Models\BaseAttachmentModel;
use App\Models\Scene;
use App\Models\BuyClass;
use App\Models\CostClass;
use App\Models\WarnLine;
use App\Models\BackMoney;
use App\Models\FoodSet;
use Carbon\Carbon;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('coupons')->truncate();
        DB::table('coupon_user')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_send')->truncate();
        DB::table('addresses')->truncate();
        DB::table('costs')->truncate();
        DB::table('inventories')->truncate();
        DB::table('activities')->truncate();
        DB::table('goods')->truncate();
        DB::table('recommend_prize')->truncate();
        DB::table('contact_uses')->truncate();
        DB::table('foods')->truncate();
        DB::table('scenes')->truncate();
        DB::table('buy_class')->truncate();
        DB::table('base_dictionary_option')->truncate();
        DB::table('cost_class')->truncate();
        DB::table('warn_line')->truncate();
        DB::table('back_money')->truncate();

        $man_food_img = BaseAttachmentModel::where('class', '男士餐图')->first();
        $woman_food_img = BaseAttachmentModel::where('class', '女士餐图')->first();
        $work_food_img = BaseAttachmentModel::where('class', '工作餐图')->first();

        $start_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->toDateTimeString();
        $end_time = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addMonth()->toDateTimeString();
        $start_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->toDateString();
        $end_date = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1, 0)->addMonth()->toDateString();

        Food::create([
            'name' => '男士餐',
            'money' => 50,
            'type' => 1,
            'poster' => $man_food_img->url,
        ]);

        Food::create([
            'name' => '女士餐',
            'money' => 45,
            'type' => 2,
            'poster' => $woman_food_img->url,
        ]);

        Food::create([
            'name' => '工作餐',
            'money' => 55,
            'type' => 3,
            'a_min' => 3,
            'a_max' => 5,
            'a_price' => 50,
            'b_min' => 6,
            'b_price' => 45,
            'poster' => $work_food_img->url,
        ]);

        Scene::create([
            'name' => '加工中心',
            'sort' => 1,
            'status' => 1
        ]);

        Scene::create([
            'name' => '实体店',
            'sort' => 2,
            'status' => 1
        ]);

        Scene::create([
            'name' => '办公室',
            'sort' => 3,
            'status' => 1
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'send',
            'dictionary_code' => 'money',
            'key' => 'money',
            'value' => '3',
            'name' => '3'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'inventory',
            'dictionary_code' => 'old_years',
            'key' => 'five',
            'value' => '5',
            'name' => '5年'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'inventory',
            'dictionary_code' => 'old_years',
            'key' => 'five',
            'value' => '3',
            'name' => '3年'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'inventory',
            'dictionary_code' => 'old_years',
            'key' => 'five',
            'value' => '2',
            'name' => '2年'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'inventory',
            'dictionary_code' => 'old_rate',
            'key' => 'five',
            'value' => '5',
            'name' => '0.5'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'inventory',
            'dictionary_code' => 'old_rate',
            'key' => 'five',
            'value' => '3',
            'name' => '0.3'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'inventory',
            'dictionary_code' => 'old_rate',
            'key' => 'five',
            'value' => '2',
            'name' => '0.2'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'costs',
            'dictionary_code' => 'times',
            'key' => '1',
            'value' => '1',
            'name' => '一个月'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'costs',
            'dictionary_code' => 'times',
            'key' => '6',
            'value' => '6',
            'name' => '半年'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'costs',
            'dictionary_code' => 'times',
            'key' => '12',
            'value' => '12',
            'name' => '一年'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'prices',
            'dictionary_code' => 'foods',
            'key' => 'woman',
            'value' => 'woman',
            'name' => '45'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'prices',
            'dictionary_code' => 'foods',
            'key' => 'man',
            'value' => 'man',
            'name' => '55'
        ]);

        DB::table('base_dictionary_option')->insert([
            'dictionary_table_code' => 'prices',
            'dictionary_code' => 'foods',
            'key' => 'work',
            'value' => 'work',
            'name' => '55'
        ]);

        BuyClass::create([
            'name' => '包材消耗',
            'sort' => 0,
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '干杂调料',
            'sort' => 0,
            'is_direct_cost' => 0,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '食材',
            'sort' => 0,
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '设备',
            'sort' => 5,
            'fa_class' => '包材消耗',
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '耗材',
            'sort' => 10,
            'fa_class' => '包材消耗',
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '包房干杂',
            'sort' => 5,
            'fa_class' => '干杂调料',
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '厨房干杂',
            'sort' => 10,
            'fa_class' => '干杂调料',
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '蔬菜',
            'sort' => 5,
            'fa_class' => '食材',
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        BuyClass::create([
            'name' => '水果',
            'sort' => 10,
            'fa_class' => '食材',
            'is_direct_cost' => 1,
            'status' => 1
        ]);

        CostClass::create([
            'name' => '房租',
            'sort' => 1,
            'status' => 1
        ]);

        CostClass::create([
            'name' => '水电',
            'sort' => 2,
            'status' => 1
        ]);

        CostClass::create([
            'name' => '工资',
            'sort' => 3,
            'status' => 1
        ]);

        WarnLine::create([
            'name' => '水果',
            'line' => 10,
            'status' => 1,
            'info' => '水果短缺，急需补充'
        ]);

        WarnLine::create([
            'name' => '蔬菜',
            'line' => 3,
            'status' => 1,
            'info' => '蔬菜短缺，急需补充'
        ]);

        BackMoney::create([
            'status' => 0,
            'method' => 0,
            'user_id' => 11,
            'username' => 'silent',
            'tel' => 123456
        ]);

        ContactUs::create([
            'key' => 'tel',
            'val' => '1234567'
        ]);

        ContactUs::create([
            'key' => 'two_code',
            'val' => '/upload/user/20170813/ab54f8e446bbbd32ce783d3a1008dae3.png'
        ]);

        ContactUs::create([
            'key' => 'pay_code',
            'val' => '/upload/user/20170926/7f2039cb39119bb123669a8ab706d573.png'
        ]);

        RecommendPrize::create([
            'name' => '推荐有礼返5%',
            'condition' => 500,
            'back_money' => 5,
            'status' => 1,
            'info' => '消费满500元可开通，开通后推荐给好友的订单返还5%'
        ]);

        RecommendPrize::create([
            'name' => '推荐有礼返10%',
            'condition' => 800,
            'back_money' => 10,
            'status' => 1,
            'info' => '消费满500元可开通，开通后推荐给好友的订单返还10%'
        ]);

        Good::create([
            'score' => 100,
            'type' => 0,
            'name' => '活动1',
            'poster' => '/upload/user/20170811/8a3d23e3d2afcd284f92d683d538c6df.png'
        ]);

        Good::create([
            'score' => 100,
            'type' => 0,
            'name' => '活动2',
            'poster' => '/upload/user/20170811/eadb1668de4ce3a662c5041c9dcc32d6.png'
        ]);

        Good::create([
            'score' => 100,
            'type' => 0,
            'name' => '活动3',
            'poster' => '/upload/user/20170811/facffdc90e5639de453a01d16e33ea19.png'
        ]);

        Activity::create([
            'name' => '满20次优惠50元',
            'type' => 0,
            'money' => 50,
            'times' => 20,
            'status' => 1,
            'description' => '满20次优惠50元',
            'begin_time' => '2017-01-01',
            'end_time' => '2018-01-01'
        ]);

        $faker = Faker\Factory::create('zh_CN');

        // Inventory::create([
        //     'scene' => '加工中心',
        //     'two_level' => '包材消耗',
        //     'three_level' => '设备',
        //     'name' => '水果',
        //     'type' => 1,
        //     'num' => 15,
        //     'total_money' => 15,
        //     'last_out_time' => $faker->date('2017-08-01', '2017-09-01')
        // ]);

        Inventory::create([
            'scene' => '加工中心',
            'two_level' => '包材消耗',
            'three_level' => '设备',
            'name' => '水果',
            'type' => 0,
            'num' => 5,
            'total_money' => 5,
            'last_enter_time' => $faker->date($start_date, $end_date)
        ]);

        Inventory::create([
            'scene' => '加工中心',
            'two_level' => '包材消耗',
            'three_level' => '设备',
            'name' => '蔬菜',
            'type' => 1,
            'num' => 15,
            'total_money' => 15,
            'last_out_time' => $faker->date($start_date, $end_date)
        ]);

        Inventory::create([
            'scene' => '加工中心',
            'two_level' => '包材消耗',
            'three_level' => '设备',
            'name' => '蔬菜',
            'type' => 0,
            'num' => 5,
            'total_money' => 5,
            'last_out_time' => $faker->date($start_date, $end_date)
        ]);
        
        Inventory::create([
            'scene' => '加工中心',
            'two_level' => '包材消耗',
            'three_level' => '设备',
            'name' => '蔬菜',
            'type' => 0,
            'num' => 15,
            'total_money' => 15,
            'last_out_time' => $faker->date($start_date, $end_date)
        ]);

        for($i = 0; $i < 10; ++$i) {

            Inventory::create([
                'scene' => '加工中心',
                'two_level' => '包材消耗',
                'three_level' => '设备',
                'name' => '蔬菜',
                'type' => 1,
                'num' => 5,
                'total_money' => 50,
                'last_out_time' => $faker->date($start_date, $end_date)
            ]);
            
            Inventory::create([
                'scene' => '加工中心',
                'two_level' => '包材消耗',
                'three_level' => '设备',
                'name' => '蔬菜',
                'type' => 0,
                'num' => 15,
                'total_money' => 50,
                'last_out_time' => $faker->date($start_date, $end_date)
            ]);

            $name = $faker->name;
            $user = User::create([
                'wechat_id' => '' . $faker->randomNumber(8, true),
                'wechat_name' => $name,
                'real_name' => $name,
                'gender' => $faker->numberBetween(1, 2),
                'birthday' => $faker->date,
                'weight' => $faker->numberBetween(50, 100) . 'kg',
                'height' => $faker->numberBetween(160, 190) . 'cm',
                'tel' => '13' . $faker->randomNumber(9, true),
                'address' => $faker->address
            ]);

            DB::table('addresses')->insert([
                'user_id' => $user->id,
                'address' => $user->address
            ]);

            $money = $faker->randomElement([5, 10, 20, 30, 50, 100]);
            $condition = $faker->randomElement([200, 300, 500, 700, 80, 1000]);

            $coupon = Coupon::create([
                'name' => '' . $money . '元优惠券',
                'type' => $faker->numberBetween(0, 3),
                'condition' => $condition,
                'money' => $money,
                'status' => 1,
                'total' => $faker->numberBetween(100, 1000),
                'use_total' => $faker->numberBetween(20, 80),
                'begin_time' => $faker->date,
                'end_time' => $faker->date,
                'description' => '满' . $condition . '减' . $money,
            ]);

            // DB::table('coupon_user')->insert([
            //     'user_id' => 1,
            //     'coupon_id' => $coupon->id,
            //     'status' => $faker->numberBetween(0, 1),
            //     'created_at' => $faker->dateTimeThisYear('now', date_default_timezone_get()),
            //     'updated_at' => $faker->dateTimeThisYear('now', date_default_timezone_get()),
            // ]);

            // DB::table('coupon_user')->insert([
            //     'user_id' => 11,
            //     'coupon_id' => $coupon->id,
            //     'status' => $faker->numberBetween(0, 1),
            //     'created_at' => $faker->dateTimeThisYear('now', date_default_timezone_get()),
            //     'updated_at' => $faker->dateTimeThisYear('now', date_default_timezone_get()),
            // ]);

            Cost::create([
                'scene' => '加工中心',
                'type' => '房租',
                'time' => '一年',
                'money' => $faker->numberBetween(50, 200),
                'add_date' => $faker->date($start_date, $end_date)
            ]);

            // $items = array();
            // $num = $faker->numberBetween(1, 10);
            // $total = 0;
            // for($j = 0; $j < $num; ++$j) {
            //     $item = $faker->numberBetween(1, 5);
            //     $total += $item;
            //     array_push($items, $item);
            // }

            // $type = $faker->numberBetween(1, 3);
            // $price = Food::getFoodPrice($type);
            // $days = $num;
            // $send_money = Food::getSendMoney() * $num;
            // $money = $price * $total + $send_money;
            // $pay_status = $faker->numberBetween(0, 1);

            // $order = Order::create([
            //     'user_id' => $user->id,
            //     'wechat_id' => $user->wechat_id,
            //     'wechat_name' => $user->wechat_name,
            //     'real_name' => $user->real_name,
            //     'tel' => $user->tel,
            //     'money' => $money,
            //     'send_money' => $send_money,
            //     'type' => $type,
            //     'days' => $days,
            //     'num' => $total,
            //     'pay_status' => $pay_status,
            //     'status' => $faker->numberBetween(0, 1),
            //     'created_at' => $faker->dateTimeBetween($start_time, $end_time),
            //     'updated_at' => $faker->dateTimeBetween($start_time, $end_time),
            // ]);

            // if($pay_status == 1) {

            //     for($j = 0; $j < $num; ++$j) {
            //         DB::table('order_send')->insert([
            //             'user_id' => $user->id,
            //             'order_id' => $order->id,
            //             'name' => $user->real_name,
            //             'tel' => $user->tel,
            //             'type' => $type,
            //             'num' => $items[$j],
            //             'price' => $price,
            //             'time' => $faker->dateTimeBetween($start_time, $end_time),
            //             'address' => $faker->address,
            //             'status' => $faker->numberBetween(0, 4),
            //             'created_at' => $faker->dateTimeBetween($start_time, $end_time),
            //             'updated_at' => $faker->dateTimeBetween($start_time, $end_time),
            //         ]);
            //     }
            // }
        }
        $user = User::create([
            'wechat_id' => '123456',
            'wechat_name' => 'silent',
            'real_name' => '李四',
            'tel' => '123456',
            'password' => bcrypt('123456'),
            'remain_money' => 10000,
            'recommend' => 1,
            'recommend_by' => 11
        ]);

        $food_sets = FoodSet::all();

        // foreach($food_sets as $food_set) {
        //     $total = ($food_set->kind == 0 ? 5 : 20);

        //     // 未支付
        //     $order = Order::create([
        //         'user_id' => $user->id,
        //         'wechat_id' => $user->wechat_id,
        //         'wechat_name' => $user->wechat_name,
        //         'real_name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'money' => $food_set->money,
        //         'send_money' => 5,
        //         'type' => $food_set->type,
        //         'food_set' => $food_set->kind,
        //         'days' => 3,
        //         'num' => $total,
        //         'pay_status' => 0,
        //         'status' => 0,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);

        //     // 已支付
        //     $order = Order::create([
        //         'user_id' => $user->id,
        //         'wechat_id' => $user->wechat_id,
        //         'wechat_name' => $user->wechat_name,
        //         'real_name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'money' => $food_set->money,
        //         'send_money' => 5,
        //         'type' => $food_set->type,
        //         'days' => 3,
        //         'num' => $total,
        //         'pay_status' => 1,
        //         'status' => 0,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);

        //     $price = round($food_set->money / $total, 2);
        //     $today = Carbon::now()->toDateString();
        //     $yesterday = Carbon::now()->subDay()->toDateString();
        //     $tomorrow = Carbon::now()->addDay()->toDateString();

        //     DB::table('order_send')->insert([
        //         'user_id' => $user->id,
        //         'order_id' => $order->id,
        //         'name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'type' => $food_set->type,
        //         'num' => 1,
        //         'price' => $price,
        //         'time' => $yesterday,
        //         'address' => $faker->address,
        //         'status' => 0,
        //         'created_at' => Carbon::now()->subDay(),
        //         'updated_at' => Carbon::now()->subDay(),
        //     ]);

        //     DB::table('order_send')->insert([
        //         'user_id' => $user->id,
        //         'order_id' => $order->id,
        //         'name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'type' => $food_set->type,
        //         'num' => 1,
        //         'price' => $price,
        //         'time' => $today,
        //         'address' => $faker->address,
        //         'status' => 0,
        //         'created_at' => Carbon::now()->subDay(),
        //         'updated_at' => Carbon::now()->subDay(),
        //     ]);

        //     DB::table('order_send')->insert([
        //         'user_id' => $user->id,
        //         'order_id' => $order->id,
        //         'name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'type' => $food_set->type,
        //         'num' => 1,
        //         'price' => $price,
        //         'time' => $tomorrow,
        //         'address' => $faker->address,
        //         'status' => 0,
        //         'created_at' => Carbon::now()->subDay(),
        //         'updated_at' => Carbon::now()->subDay(),
        //     ]);

        //     // 已完成
        //     $order = Order::create([
        //         'user_id' => $user->id,
        //         'wechat_id' => $user->wechat_id,
        //         'wechat_name' => $user->wechat_name,
        //         'real_name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'money' => $food_set->money,
        //         'send_money' => 5,
        //         'type' => $food_set->type,
        //         'days' => 1,
        //         'num' => $total,
        //         'pay_status' => 1,
        //         'status' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);

        //     DB::table('order_send')->insert([
        //         'user_id' => $user->id,
        //         'order_id' => $order->id,
        //         'name' => $user->real_name,
        //         'tel' => $user->tel,
        //         'type' => $food_set->type,
        //         'num' => 1,
        //         'price' => $price,
        //         'time' => $yesterday,
        //         'address' => $faker->address,
        //         'status' => 0,
        //         'created_at' => Carbon::now()->subDay(),
        //         'updated_at' => Carbon::now()->subDay(),
        //     ]);
        // }

        User::create([
            'wechat_id' => '11111',
            'wechat_name' => 'user1',
            'real_name' => '用户1',
            'tel' => '21111',
            'password' => bcrypt('123456')
        ]);

        User::create([
            'wechat_id' => '22222',
            'wechat_name' => 'user2',
            'real_name' => '用户2',
            'tel' => '31111',
            'password' => bcrypt('123456')
        ]);
    }
}
