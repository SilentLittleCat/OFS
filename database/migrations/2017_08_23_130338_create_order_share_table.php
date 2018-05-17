<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderShareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_share', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_order_id')->comment('发起人订单ID');
            $table->unsignedInteger('order_id')->comment('自己订单ID');
            $table->unsignedInteger('user_id')->comment('自己用户ID');
            $table->string('wechat_id', 255)->nullable()->comment('自己微信ID');
            $table->string('wechat_name', 255)->nullable()->comment('自己微信昵称');
            $table->string('real_name', 255)->nullable()->comment('自己实名');
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
        Schema::dropIfExists('order_share');
    }
}
