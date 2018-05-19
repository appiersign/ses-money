<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stan', 12)->unique()->index();
            $table->string('merchant_id', 12)->index();
            $table->string('transaction_id', 12)->index();
            $table->string('account_number', 16)->index();
            $table->string('provider', 3);
            $table->string('amount', 12);
            $table->string('description', 100);
            $table->string('response_url');
            $table->string('authorization_code')->nullable();
            $table->string('external_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('narration')->nullable();
            $table->string('response_code')->nullable();
            $table->string('response_status')->nullable();
            $table->string('response_message')->nullable();
            $table->foreign('merchant_id')->references('merchant_id')->on('merchants')->onDelete('cascade');
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
        Schema::dropIfExists('transfers');
    }
}
