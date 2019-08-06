<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BusinessUpdateFour extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->tinyInteger('franchise')->nullable()->comment('franchise');
            $table->text('employee_info')->nullable()->comment('employee info');
            $table->text('franchise_reports')->nullable()->comment('franchise reports');
            $table->text('tax_returns')->nullable()->comment('tax returns');
        });
        Schema::table('business_zh', function (Blueprint $table) {
            $table->tinyInteger('franchise')->nullable()->comment('franchise');
            $table->text('employee_info')->nullable()->comment('employee info');
            $table->text('franchise_reports')->nullable()->comment('franchise reports');
            $table->text('tax_returns')->nullable()->comment('tax returns');
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
