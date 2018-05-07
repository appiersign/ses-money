<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->index();
            $table->string('ses_money_id')->unique()->index();
            $table->string('merchant_id')->unique()->index();
            $table->string('api_user')->unique()->index();
            $table->string('api_key');
            $table->string('email')->unique()->index();
            $table->string('password');
            $table->string('phone_number');
            $table->string('address');
            $table->string('actions')->default('payment');
            $table->decimal('limit')->default(500.00);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('merchants');
    }
}
