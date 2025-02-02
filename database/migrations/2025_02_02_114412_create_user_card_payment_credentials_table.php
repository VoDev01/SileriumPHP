<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_card_payment_credentials', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('number', 1000);
            $table->string('expiry', 1000);
            $table->string('ccv', 1000);
            $table->foreignUlid('user_id')->references('ulid')->on('users');
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
        Schema::dropIfExists('user_card_payment_credentials');
    }
};
