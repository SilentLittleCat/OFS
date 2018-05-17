<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAfterSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_after_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('order_id')->nullable()->comment('订单ID');
            $table->unsignedInteger('order_send_id')->nullable()->comment('配送订单ID');
            $table->tinyInteger('gender')->nullable()->comment('性别,1:男,2:女,参照数据字典');
            $table->string('name', 255)->nullable()->comment('收货人');
            $table->string('price', 50)->nullable()->comment('单价');
            $table->string('tel', 100)->nullable()->comment('手机号');
            $table->tinyInteger('type')->nullable()->default(1)->comment('餐类:1,男士餐;2,女士餐;3,工作餐');
            $table->unsignedInteger('num')->nullable()->default(0)->comment('份数');
            $table->string('time', 50)->nullable()->comment('配送时间');
            $table->string('img', 1000)->nullable()->comment('照片');
            $table->string('reason', 1000)->nullable()->comment('原因');
            $table->string('address', 255)->nullable()->comment('配送地址');
            $table->tinyInteger('status')->default(0)->comment('状态:0待审核，1已退款，2审核不过');
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
        Schema::dropIfExists('order_after_sales');
    }
}
