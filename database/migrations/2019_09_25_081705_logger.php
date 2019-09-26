<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Logger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("loggers", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url',250)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('method',250)->nullable();
            $table->string('is_ajax',250)->nullable();
            $table->text('header')->nullable();
            $table->string('cookies',250)->nullable();
            $table->string('query',250)->nullable();
            $table->string('post',250)->nullable();
            $table->string('json',250)->nullable();
            $table->string('client_ip',250)->nullable();
            $table->string('client_ips',250)->nullable();
            $table->string('agent',250)->nullable();
            $table->string('host',250)->nullable();
            $table->string('scheme',250)->nullable();
            $table->timestamp('created_at')->nullable();
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
