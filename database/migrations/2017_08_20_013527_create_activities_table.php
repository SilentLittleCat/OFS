<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('活动名');
            $table->tinyInteger('type')->comment('活动类型:0,通用范围;1,男士;2,女士;3,工作餐');
            $table->string('money', 50)->comment('金额');
            $table->unsignedInteger('times')->comment('购买次数满');
            $table->tinyInteger('status')->comment('状态:0,禁用;1,启用');
            $table->string('description', 500)->nullable()->comment('规则描述');
            $table->string('begin_time', 20)->comment('开时时间');
            $table->string('end_time', 20)->comment('结束时间');
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
        Schema::dropIfExists('activities');
    }
}
