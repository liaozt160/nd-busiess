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
            $table->string('remarks',250)->nullable()->default(null)->comment('remarks');
            $table->tinyInteger('status')->nullable()->default(1)->comment('status,1 normal,2 disabled ,3 sold,');
        });

        Schema::table('buyer', function (Blueprint $table) {
            $table->string('remarks',250)->nullable()->default(null)->comment('remarks');
            $table->tinyInteger('status')->nullable()->default(1)->comment('status,1 normal,2 disabled ,');
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
