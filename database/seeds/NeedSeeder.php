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
use Carbon\Carbon;

class NeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('zh_CN');

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

        // DB::table('base_dictionary_option')->insert([
        //     'dictionary_table_code' => 'prices',
        //     'dictionary_code' => 'foods',
        //     'key' => 'woman',
        //     'value' => 'woman',
        //     'name' => '45'
        // ]);

        // DB::table('base_dictionary_option')->insert([
        //     'dictionary_table_code' => 'prices',
        //     'dictionary_code' => 'foods',
        //     'key' => 'man',
        //     'value' => 'man',
        //     'name' => '55'
        // ]);

        // DB::table('base_dictionary_option')->insert([
        //     'dictionary_table_code' => 'prices',
        //     'dictionary_code' => 'foods',
        //     'key' => 'work',
        //     'value' => 'work',
        //     'name' => '55'
        // ]);

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

        BackMoney::create([
            'status' => 0,
            'method' => 0,
            'user_id' => 11,
            'username' => 'silent',
            'tel' => 123
        ]);

        ContactUs::create([
            'key' => 'tel',
            'val' => '1234567'
        ]);

        ContactUs::create([
            'key' => 'two_code',
            'val' => '/upload/user/20170813/ab54f8e446bbbd32ce783d3a1008dae3.png'
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
    }
}
