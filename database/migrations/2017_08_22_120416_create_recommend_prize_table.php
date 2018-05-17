<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommendPrizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommend_prize', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('名称');
            $table->unsignedInteger('condition')->nullable()->comment('条件，消费满多少');
            $table->unsignedInteger('back_money')->nullable()->comment('好友订单返现比例');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态:0,禁用;1,启用;');
            $table->string('info', 400)->nullable()->comment('活动简介');
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
        Schema::dropIfExists('recommend_prize');
    }
}
