<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scene', 50)->comment('使用场景');
            $table->string('type', 50)->comment('费用类型');
            $table->string('time', 50)->comment('单位时间');
            $table->string('money', 50)->nullable()->comment('金额');
            $table->string('add_date', 50)->nullable()->comment('添加时间');
            $table->string('username', 50)->nullable()->comment('添加人');
            $table->string('remarks', 200)->nullable()->comment('备注信息');
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
        Schema::dropIfExists('costs');
    }
}
