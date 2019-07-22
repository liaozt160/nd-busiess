<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderPayInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_order_pay_information',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('order_id');
            $table->tinyInteger('payment')->default(null)->nullable()->comment('支付款项');
            $table->bigInteger('amount')->default(0)->nullable()->comment('amount');
            $table->tinyInteger('verification')->default(null)->nullable()->comment('验证、核实');
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
