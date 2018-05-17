<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->tinyInteger('status')->nullable()->default(0)->comment('状态：0，获得积分；1，使用积分');
            $table->unsignedInteger('coupon_id')->nullable()->comment('兑换的优惠券ID');
            $table->unsignedInteger('order_id')->nullable()->comment('订单ID');
            $table->string('name', 200)->nullable()->comment('兑换的商品名');
            $table->unsignedInteger('score')->comment('使用的积分');
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
        Schema::dropIfExists('scores');
    }
}
