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
        DB::statement('ALTER TABLE `users` CHANGE `email_verified` `emailVerified` TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE `users` CHANGE `phoneVerified` `phoneVerified` TINYINT(1) NOT NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `users` CHANGE `emailVerified` `email_verified` TINYINT(1) NULL');
        DB::statement('ALTER TABLE `users` CHANGE `phoneVerified` `phoneVerified` TINYINT(1) NULL');
    }
};
