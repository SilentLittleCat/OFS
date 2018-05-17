<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('score')->comment('兑换积分');
            $table->unsignedInteger('coupon_id')->nullable()->comment('优惠券ID');
            $table->tinyInteger('type')->nullable()->default(0)->comment('积分商品类型：0，实物；1，优惠券');
            $table->string('name', 100)->comment('商品名');
            $table->string('poster', 255)->nullable()->comment('海报');
            $table->string('info', 400)->nullable()->default('暂无简介')->comment('简介');
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
        Schema::dropIfExists('goods');
    }
}
