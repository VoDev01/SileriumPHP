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
        Schema::table('banned_users', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('banned_users', function (Blueprint $table) {
            $table->string('user_id', 200)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banned_users', function (Blueprint $table) {
            $table->foreign('user_id')->references('ulid')->on('users')->onDelete('cascade');
        });
    }
};
