<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('send_id')->nullable()->comment('配送ID');
            $table->unsignedInteger('user_id')->comment('修改管理员ID');
            $table->string('username', 255)->comment('修改管理员名称');
            $table->tinyInteger('food_type')->nullable()->comment('预定餐类:1,男士餐;2,女士餐;3,工作餐');
            $table->string('back_money', 50)->nullable()->comment('退款金额');
            $table->tinyInteger('type')->comment('修改操作类型：1，更换配餐；2，更换地址；3，延时配送；4，取消配送；5，设置为配送中；6，退款；7，全部退款;8,已完成');
            $table->string('info', 100)->nullable()->comment('详细信息');
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
        Schema::dropIfExists('send_changes');
    }
}
