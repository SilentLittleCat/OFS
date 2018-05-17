<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('back_money', function (Blueprint $table) {
            $table->increments('id');
            $table->string('money', 50)->comment('提现金额');
            $table->tinyInteger('status')->nullable()->default(0)->comment('状态:0,待审核;1,审核通过;2,已打款;3,审核不过;');
            $table->tinyInteger('method')->nullable()->default(0)->comment('提现方式:0,微信二维码;');
            $table->unsignedInteger('user_id')->comment('申请人ID');
            $table->string('img', 200)->nullable()->comment('提现二维码');
            $table->string('username', 200)->nullable()->comment('申请人');
            $table->string('tel', 200)->nullable()->comment('申请人手机');
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
        Schema::dropIfExists('back_money');
    }
}
