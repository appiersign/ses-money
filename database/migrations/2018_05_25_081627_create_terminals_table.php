<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('merchant_id')->index();
            $table->string('ses_money_id')->index()->nullable();
            $table->string('name')->index();
            $table->string('type')->default('web');
            $table->string('pin')->default('$2y$10$QbRGismi0uPbVV81dCoiHOJCpQTS5IPb93SDlUzwXjS2n1qJc/Rk2')->comment('default pin is 0000');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('terminals', function (Blueprint $table){
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminals');
    }
}
