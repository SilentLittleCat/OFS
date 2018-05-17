<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->comment('订单ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('wechat_id', 255)->comment('用户微信ID');
            $table->string('wechat_name', 255)->comment('下单人微信昵称');
            $table->string('real_name', 255)->nullable()->comment('下单人实名');
            $table->tinyInteger('gender')->nullable()->comment('性别,1:男,2:女,参照数据字典');
            $table->tinyInteger('food_set')->nullable()->default(0)->comment('套餐类型：0周餐，1月餐，2自定义');
            $table->string('tel', 100)->nullable()->comment('下单人手机');
            $table->string('money', 50)->comment('订单金额');
            $table->string('price', 50)->nullable()->default(0)->comment('单价');
            $table->string('discount_money', 50)->nullable()->comment('折扣价格');
            $table->tinyInteger('method')->nullable()->default(0)->comment('购买方式:0,普通方式;1,购买多次');
            $table->tinyInteger('type')->comment('预定餐类:1,男士餐;2,女士餐;3,工作餐');
            $table->unsignedInteger('use_remain_times')->nullable()->default(0)->comment('使用剩余次数');
            $table->unsignedInteger('use_coupon')->nullable()->default(0)->comment('使用优惠券，0，否；1以上为优惠券ID');
            $table->unsignedInteger('use_act')->nullable()->default(0)->comment('参加活动，0，否；1以上为活动ID');
            $table->tinyInteger('is_prize')->nullable()->default(0)->comment('参加活动，0，否；1，是');
            $table->unsignedInteger('prize_money')->nullable()->comment('奖励金额');
            $table->unsignedInteger('send_money')->nullable()->comment('运费');
            $table->unsignedInteger('score')->nullable()->comment('积分');
            $table->unsignedInteger('num')->default(0)->comment('份数');
            $table->unsignedInteger('days')->nullable()->default(0)->comment('天数');
            $table->unsignedInteger('recommend')->nullable()->default(0)->comment('推荐人');
            $table->string('dates', 300)->nullable()->comment('预定时间');
            $table->string('dates_details', 300)->nullable()->comment('预定详情');
            $table->tinyInteger('pay_status')->default(0)->comment('付款状态:0,未支付;1,已支付');
            $table->string('address', 255)->nullable()->comment('配送地址');
            $table->tinyInteger('status')->default(0)->comment('订单状态:0,进行中;1,已完成,2，已退全款');
            $table->string('remarks', 300)->nullable()->default('无')->comment('备注');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
