<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_send', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('order_id')->nullable()->comment('订单ID');
            $table->tinyInteger('gender')->nullable()->comment('性别,1:男,2:女,参照数据字典');
            $table->string('name', 255)->nullable()->comment('收货人');
            $table->string('price', 50)->nullable()->comment('单价');
            $table->string('tel', 100)->comment('手机号');
            $table->tinyInteger('type')->comment('餐类:1,男士餐;2,女士餐;3,工作餐');
            $table->unsignedInteger('num')->default(0)->comment('份数');
            $table->string('time', 50)->comment('配送时间');
            $table->string('address', 255)->nullable()->comment('配送地址');
            $table->tinyInteger('status')->default(0)->comment('状态:0,未配送;1,配送中;2,已完成;3,已取消;4,已退款');
            $table->string('remarks', 300)->nullable()->comment('备注');
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
        Schema::dropIfExists('order_send');
    }
}
