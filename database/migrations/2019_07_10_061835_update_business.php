<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->decimal('price',10,0)->nullable()->comment('business price')->change();
            $table->decimal('gross_income',10,0)->nullable()->comment('毛利润 EX. $8300/month')->change();
            $table->string('gross_income_unit',50)->nullable()->after('gross_income')->default(null)->comment('gross income unit');
            $table->decimal('net_income',10,0)->nullable()->comment('Net Income 净利润 $25,000/month')->change();
            $table->string('net_income_unit',50)->nullable()->after('net_income')->default(null)->comment('net income unit');
            $table->decimal('lease',10,0)->nullable()->comment('租金 $25,000/month')->change();
            $table->string('lease_unit',50)->nullable()->after('lease')->default(null)->comment('lease unit');
        });

        Schema::table('business_zh', function (Blueprint $table) {
            $table->decimal('price',10,0)->nullable()->comment('business price')->change();
            $table->decimal('gross_income',10,0)->nullable()->comment('毛利润 EX. $8300/month')->change();
            $table->string('gross_income_unit',50)->nullable()->after('gross_income')->default(null)->comment('gross income unit');
            $table->decimal('net_income',10,0)->nullable()->comment('Net Income 净利润 $25,000/month')->change();
            $table->string('net_income_unit',50)->nullable()->after('net_income')->default(null)->comment('net income unit');
            $table->decimal('lease',10,0)->nullable()->comment('租金 $25,000/month')->change();
            $table->string('lease_unit',50)->nullable()->after('lease')->default(null)->comment('lease unit');
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
