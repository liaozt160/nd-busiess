<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuyerOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('account_id')->nullable()->comment('买方中介方id');
            $table->bigInteger('buyer_id')->nullable()->comment('买方id');
            $table->bigInteger('audit_id')->nullable()->comment('审核id');
            $table->string('order_no',50)->comment('order no');
            $table->tinyInteger('paid')->default(1)->nullable()->comment('是否已支付');
            $table->bigInteger('pay_amount')->nullable()->comment('支付金额');
            $table->tinyInteger('status')->nullable()->default(1)->comment('订单状态，0待提交，1 审核中，2，已审核');
            $table->text('remark')->nullable()->comment('备注');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('audit_at')->nullable();
            $table->timestamps();
        });
        Schema::create('buyer_order_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id');
            $table->string('order_no',50)->comment('order no');
            $table->bigInteger('business_id')->nullable()->comment('待售企业id');
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
