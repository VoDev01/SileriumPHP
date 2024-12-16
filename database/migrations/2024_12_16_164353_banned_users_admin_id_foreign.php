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
        Schema::table('banned_users', function(Blueprint $table) {
            $table->ulid('admin_id')->change();
            $table->foreign('admin_id')->references('ulid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banned_users', function(Blueprint $table) {
            $table->dropForeign('admin_id');
            $table->bigInteger('admin_id')->change();
        });
    }
};
