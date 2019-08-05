<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBuyerAddAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer', function (Blueprint $table) {
            $table->string('country',50)->nullable()->before('deleted_at')->comment('country');
            $table->string('states',50)->nullable()->before('deleted_at')->comment('province');
            $table->string('city',50)->nullable()->before('deleted_at')->comment('city');
            $table->string('address',200)->nullable()->before('deleted_at')->comment('address');
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
