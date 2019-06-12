<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
//            $table->increments('id')->nullable(false)->comment('the user id');
            $table->string('email',100)->comment('email');
            $table->string('phone',100)->comment('phone');
            $table->string('name',100)->comment('name');
            $table->tinyInteger('is_agent')->default(true)->comment('name');
            $table->tinyInteger('access_level')->default(1)->comment('user access level (1,2,3)');
            $table->tinyInteger('role')->default(1)->comment('user role (1 for users ,2 for admins)');
            $table->string('password',150)->nullable();
            $table->rememberToken();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
