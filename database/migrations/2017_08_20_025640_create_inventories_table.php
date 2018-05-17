<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scene', 50)->comment('使用场景');
            $table->string('two_level', 50)->comment('二级分类');
            $table->string('three_level', 50)->comment('三级分类');
            $table->tinyInteger('is_in')->nullable()->comment('是否入库:0,否;1,是;');
            $table->tinyInteger('type')->nullable()->comment('类型:0,入库信息;1,出库信息;2,库存管理');
            $table->tinyInteger('is_direct_cost')->nullable()->comment('直接成本:0,否;1,是;');
            $table->string('name', 50)->nullable()->comment('产品名');
            $table->string('username', 50)->nullable()->comment('采购人/出库人');
            $table->string('standard', 50)->nullable()->comment('规格');
            $table->string('unit', 50)->nullable()->comment('单位');
            $table->string('num', 50)->nullable()->comment('数量');
            $table->string('price', 50)->nullable()->comment('单价');
            $table->string('total_money', 50)->nullable()->comment('总价');
            $table->string('status', 200)->nullable()->comment('状态');
            $table->string('old_years', 50)->nullable()->comment('折旧年');
            $table->string('old_rate', 50)->nullable()->comment('折旧率');
            $table->string('old_cost', 50)->nullable()->comment('折旧成本');
            $table->string('last_enter_time', 20)->nullable()->comment('进库时间');
            $table->string('last_out_time', 20)->nullable()->comment('出库时间');
            $table->string('remarks', 300)->nullable()->comment('备注');
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
        Schema::dropIfExists('inventories');
    }
}
