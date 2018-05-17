<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_class', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('分类名');
            $table->string('fa_class', 50)->nullable()->comment('父分类');
            $table->unsignedInteger('sort')->nullable()->comment('排序,数字越大越靠后;0为二级分类');
            $table->tinyInteger('is_direct_cost')->comment('直接成本:0,否;1,是');
            $table->tinyInteger('status')->comment('状态:0,禁用;1,启用;');
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
        Schema::dropIfExists('buy_class');
    }
}
