<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BusinessChangeIntChar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->string('price',250)->nullable()->comment('business price')->change();
            $table->string('building_sf',250)->nullable()->comment('营业面积')->change();
            $table->string('gross_income',250)->nullable()->comment('毛利润 EX. $8300/month')->change();
            $table->string('value_of_real_estate',250)->nullable()->comment('Est. Value of Real Estate房地产估价')->change();

            $table->string('net_income',250)->nullable()->comment('Net Income 净利润 $25,000/month')->change();
            $table->string('lease',250)->nullable()->comment('租金 $25,000/month')->change();
            $table->string('commission',250)->nullable()->comment('Commission佣金 EX. 2.5%')->change();
        });

        /**
         * business zh
         */
        Schema::table('business_zh', function (Blueprint $table) {
            $table->string('price',250)->nullable()->comment('business price')->change();
            $table->string('building_sf',250)->nullable()->comment('营业面积')->change();
            $table->string('gross_income',250)->nullable()->comment('毛利润 EX. $8300/month')->change();
            $table->string('value_of_real_estate',250)->nullable()->comment('Est. Value of Real Estate房地产估价')->change();

            $table->string('net_income',250)->nullable()->comment('Net Income 净利润 $25,000/month')->change();
            $table->string('lease',250)->nullable()->comment('租金 $25,000/month')->change();
            $table->string('commission',250)->nullable()->comment('Commission佣金 EX. 2.5%')->change();
        });

        Schema::table('buyer', function (Blueprint $table) {
            $table->string('funds_available',200)->nullable()->comment('funds available')->change();
            $table->string('desired_transaction_amount',200)->nullable()->comment('desired transaction amount')->change();
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
