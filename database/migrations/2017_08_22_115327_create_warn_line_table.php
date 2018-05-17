<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarnLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warn_line', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('分类名');
            $table->unsignedInteger('line')->nullable()->comment('警戒线');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态:0,禁用;1,启用;');
            $table->tinyInteger('color')->nullable()->default(1)->comment('状态:0,红色;1,绿色;');
            $table->string('info', 200)->nullable()->comment('提示信息');
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
        Schema::dropIfExists('warn_line');
    }
}
