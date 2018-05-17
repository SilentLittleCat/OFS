<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id')->comment('优惠券ID');
            $table->string('name', 255)->comment('优惠券名称');
            $table->tinyInteger('type')->comment('优惠券类型:0,通用范围;1,男士;2,女士;3,工作餐');
            $table->unsignedInteger('condition')->comment('使用条件');
            $table->unsignedInteger('money')->comment('优惠金额');
            $table->tinyInteger('status')->default(0)->comment('启用状态:0,禁止;1,启用');
            $table->unsignedInteger('total')->default(0)->comment('发放总量');
            $table->unsignedInteger('use_total')->default(0)->comment('使用总量');
            $table->string('description', 500)->nullable()->comment('规则描述');
            $table->string('begin_time', 50)->comment('开始时间');
            $table->string('end_time', 50)->comment('结束时间');
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
        Schema::dropIfExists('coupons');
    }
}
