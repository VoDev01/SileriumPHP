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
        Schema::dropIfExists('banned_api_users');
        Schema::create('banned_api_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ulid('api_user_id');
            $table->foreign('api_user_id')->references('id')->on('api_users');
            $table->ipAddress('userIp');
            $table->ulid('admin_id');
            $table->foreign('admin_id')->references('ulid')->on('users');
            $table->string('reason', 1000);
            $table->integer('duration');
            $table->char('timeType', 10);
            $table->dateTime('bannedAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banned_api_users');
    }
};
