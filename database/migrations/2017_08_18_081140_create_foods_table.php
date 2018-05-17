<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('商品名');
            $table->string('money', 50)->nullable()->comment('单价');
            $table->tinyInteger('type')->comment('预定餐类:1,男士餐;2,女士餐;3,工作餐');
            $table->unsignedInteger('a_min')->nullable()->comment('第一级别下限人数');
            $table->unsignedInteger('a_max')->nullable()->comment('第一级别上限人数');
            $table->unsignedInteger('b_min')->nullable()->comment('第二级别下限人数');
            $table->string('a_price', 50)->nullable()->comment('第一级别价格价格');
            $table->string('b_price', 50)->nullable()->comment('6-10人价格');
            $table->string('max_people', 50)->nullable()->comment('最多订餐人数');
            $table->string('poster', 255)->nullable()->comment('海报');
            $table->string('info', 400)->nullable()->default('暂无简介')->comment('简介');
            $table->tinyInteger('status')->default(1)->comment('0,下架;1,上架');
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
        Schema::dropIfExists('foods');
    }
}
