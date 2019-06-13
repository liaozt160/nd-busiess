<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('buyer_broker')->nullable()->comment('买方中介方');
            $table->string('buyer',200)->nullable()->comment('buyer name');
            $table->string('email',200)->nullable()->comment('buyer email');
            $table->string('phone',200)->nullable()->comment('buyer name');
            $table->integer('funds_available')->nullable()->comment('funds available');
            $table->integer('desired_transaction_amount')->nullable()->comment('desired transaction amount');
            $table->tinyInteger('funds_verified')->nullable()->comment('Funds verified');

            $table->text('specific_skills_of_buyer')->nullable()->comment('specific skills of buyer');
            $table->text('business_management_needs')->nullable()->comment('business Management needs');
            $table->text('time_line_to_purchase')->nullable()->comment('time line to purchase');

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
        Schema::dropIfExists('buyer');
    }
}
