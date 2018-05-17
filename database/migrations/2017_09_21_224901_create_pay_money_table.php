<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_money', function (Blueprint $table) {
            $table->increments('id');
            $table->string('money', 50)->comment('充值金额');
            $table->tinyInteger('status')->nullable()->default(0)->comment('状态:0,待审核;1,已付款;2,未付款;');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('username', 200)->nullable()->comment('用户名');
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
        Schema::dropIfExists('pay_money');
    }
}
