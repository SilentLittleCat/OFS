<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAmendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_amend', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('订单人ID');
            $table->unsignedInteger('amend_user_id')->comment('修改人ID，管理用户ID');
            $table->string('amend_user_name', 200)->comment('修改人姓名，管理用户姓名');
            $table->tinyInteger('type')->comment('餐类:1,男士餐;2,女士餐;3,工作餐');
            $table->integer('origin_times')->default(0)->comment('原有次数');
            $table->integer('amend_times')->default(0)->comment('修改的次数');
            $table->string('reason', 255)->nullable()->comment('修改原因');
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
        Schema::dropIfExists('order_amend');
    }
}
