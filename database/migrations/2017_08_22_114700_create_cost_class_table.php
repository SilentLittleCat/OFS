<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_class', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('分类名');
            $table->unsignedInteger('sort')->nullable()->comment('排序,数字越大越靠后');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态:0,禁用;1,启用;');
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
        Schema::dropIfExists('cost_class');
    }
}
