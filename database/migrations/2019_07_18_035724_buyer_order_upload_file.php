<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuyerOrderUploadFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_order_upload_files',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->comment("订单ID");
            $table->bigInteger('file_id')->comment("文件ID");
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
