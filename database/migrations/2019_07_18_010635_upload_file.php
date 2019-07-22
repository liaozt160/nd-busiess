<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_files',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('file',200)->nullable()->default(null)->comment("文件，hash维一码");
            $table->string('name',200)->nullable()->default(null)->comment("文件上传名称");
            $table->string('remark',255)->nullable()->default(null)->comment("备注或说明");
            $table->string('extension',100)->nullable()->default(null)->comment("文件扩展名");
            $table->string('mime_type',200)->nullable()->default(null)->comment("文件类型");
            $table->string('path',200)->nullable()->default(null)->comment("文件路径");
            $table->string('disk',100)->nullable()->default(null)->comment("磁盘，适用部份框架如laravel");
            $table->string('url',200)->nullable()->default(null)->comment("文件url地址");
            $table->string('width',20)->nullable()->default(null)->comment("用以保存图片类型宽度");
            $table->string('height',20)->nullable()->default(null)->comment("用以保存图片类型高度");
            $table->tinyInteger('status')->nullable()->default(1)->comment("状态：1，正常，0删除");
            $table->timestamp('deleted_at')->nullable();
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
        //
    }
}
