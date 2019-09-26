<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoggerUpdateCookies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loggers', function (Blueprint $table) {
            $table->text('header')->nullable()->change();
            $table->text('cookies')->nullable()->change();
            $table->text('query')->nullable()->change();
            $table->text('post')->nullable()->change();
            $table->text('json')->nullable()->change();
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
