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
        Schema::dropIfExists('api_users_roles');
        Schema::dropIfExists('banned_api_users');
        Schema::dropIfExists('api_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('api_users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name', 50);
            $table->string('email', 150);
            $table->string('password', 255);
            $table->string('phone', 20);
            $table->rememberToken();
            $table->timestamps();
        });
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
        Schema::create('api_users_roles', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained();
            $table->ulid('api_user_id');
            $table->foreign('api_user_id')->references('id')->on('api_users');
        });
    }
};
