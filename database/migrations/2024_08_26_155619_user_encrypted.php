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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 250)->change();
            $table->string('surname', 250)->change();
            $table->string('email', 350)->change();
            $table->string('country', 500)->change();
            $table->string('city', 500)->change();
            $table->string('homeAdress', 750)->change();
            $table->string('phone', 250)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 30)->change();
            $table->string('surname', 30)->change();
            $table->string('email', 75)->change();
            $table->string('country', 100)->change();
            $table->string('city', 50)->change();
            $table->string('homeAdress', 200)->change();
            $table->string('phone', 20)->change();
        });
    }
};
