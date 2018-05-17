<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateBaseAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->comment('附件名称');
            $table->string('md5', 50)->comment('md5码');
            $table->string('path', 1024)->comment('附件路径');
            $table->string('url', 1024)->comment('附件url');
            $table->string('class', 255)->default('未分类')->comment('分类');
            $table->unsignedBigInteger('size')->nullable();
            $table->string('file_type', 100)->nullable();
            $table->unsignedBigInteger('download')->default(0);
            $table->string('klass', 50)->nullable()->comment('关联模型');
            $table->unsignedInteger('objid')->nullable()->comment('关联id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('md5', 'idx_md5');
            $table->index(['klass', 'objid'], 'idx_klass_objid');

            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_attachments');
    }
}
