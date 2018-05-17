<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->nullable()->default(0)->comment('排序');
            $table->tinyInteger('type')->default(1)->comment('餐品类型:0,通用范围;1,男士;2,女士;3,工作餐');
            $table->tinyInteger('kind')->default(0)->comment('套餐类型:0,周餐(5天);1,月餐(20天)');
            $table->string('money', 50)->default(0)->comment('金额');
            $table->tinyInteger('status')->default(1)->comment('状态:0,禁用;1,启用');
            $table->string('poster', 255)->nullable()->comment('海报');
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
        Schema::dropIfExists('food_sets');
    }
}
