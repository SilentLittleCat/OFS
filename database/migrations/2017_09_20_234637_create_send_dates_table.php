<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->comment('日期');
            $table->tinyInteger('status')->default(1)->nullable()->comment('状态：0，不可配送；1，可以配送');
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
        Schema::dropIfExists('send_dates');
    }
}
