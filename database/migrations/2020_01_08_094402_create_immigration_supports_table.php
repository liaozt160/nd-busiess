<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImmigrationSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immigration_supports', function (Blueprint $table) {
            $table->bigInteger('business_id')->comment('business id');
            $table->bigInteger('immigration_support_tag_id')->comment('immigration support tag id');
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
        Schema::dropIfExists('immigration_supports');
    }
}
