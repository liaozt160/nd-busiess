<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuyerBrokerNet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_broker_net', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250)->nullable();
            $table->string('remark',250)->nullable();
            $table->bigInteger('created_by')->nullable();;
            $table->bigInteger('deleted_by')->nullable();;
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('buyer_broker_net_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('net_id')->comment('buyer broker net');
            $table->bigInteger('account_id')->comment('account id');
            $table->tinyInteger('manager')->default(0)->comment('who can manage the business broker net');
            $table->tinyInteger('viewer')->default(0)->comment('who can see all the business in  broker net');
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
