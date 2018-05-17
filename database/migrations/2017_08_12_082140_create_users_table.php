<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('用户ID');
            $table->string('wechat_id', 255)->nullable()->comment('微信ID');
            $table->string('wechat_name', 255)->nullable()->comment('微信昵称');
            $table->string('real_name', 255)->nullable()->comment('实名');
            $table->string('password', 255)->nullable()->comment('密码,测试用');
            $table->tinyInteger('gender')->nullable()->comment('性别,1:男,2:女,参照数据字典');
            $table->string('birthday', 50)->nullable()->comment('生日');
            $table->string('weight', 50)->nullable()->comment('体重');
            $table->string('height', 50)->nullable()->comment('身高');
            $table->string('tel', 100)->nullable()->comment('手机号');
            $table->string('address', 255)->nullable()->comment('收货地址');

            $table->unsignedInteger('recommend')->nullable()->default(0)->comment('推荐有礼:0,未开通;1以上为某项推荐有礼ID');
            $table->unsignedInteger('recommend_by')->nullable()->default(0)->comment('推荐人ID');
            $table->string('prize_money', 50)->nullable()->default(0)->comment('获得奖金');
            $table->string('remain_money', 50)->nullable()->default(0)->comment('账户余额');
            $table->string('total_pay', 50)->nullable()->default(0)->comment('预付金额');
            $table->string('total_consume', 50)->nullable()->default(0)->comment('实际消费金额');
            $table->unsignedInteger('score')->nullable()->default(0)->comment('积分');
            $table->unsignedInteger('man_times')->nullable()->default(0)->comment('男士餐购买次数');
            $table->unsignedInteger('woman_times')->nullable()->default(0)->comment('女士餐购买次数');
            $table->unsignedInteger('work_times')->nullable()->default(0)->comment('工作餐购买次数');
            $table->unsignedInteger('man_remain_times')->nullable()->default(0)->comment('男士餐剩余次数');
            $table->unsignedInteger('woman_remain_times')->nullable()->default(0)->comment('女士餐剩余次数');
            $table->unsignedInteger('work_remain_times')->nullable()->default(0)->comment('工作餐剩余次数');
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
