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
        Schema::create('api_users_roles', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained();
            $table->ulid('api_user_id');
            $table->foreign('api_user_id')->references('id')->on('api_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_users_roles');
    }
};
