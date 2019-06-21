<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBuyer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer', function (Blueprint $table) {
            $table->integer('services_pay_amount')->nullable()->default(null)->comment('支付服务费用金额');
            $table->tinyInteger('services_pay')->nullable()->default(1)->comment('买方是否已支付服务费用，1 未支付,2 已支付服务费 ,');
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
