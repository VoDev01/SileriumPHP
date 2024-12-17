<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
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
            $table->renameColumn("banTime", "duration");
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
        Schema::table('banned_users', function(Blueprint $table) {
            $table->renameColumn("duration", "banTime");
            $table->dropColumn('bannedAt');
        });
    }
};
