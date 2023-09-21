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
        Schema::table('users', function(Blueprint $table){
            $table->string('surname', 30)->nullable(true)->after('name');
            $table->timestamp('birthDate')->nullable(true);
            $table->string('country', 50);
            $table->string('city', 50);
            $table->string('homeAdress', 200);
            $table->string('phone')->nullable(true);
            $table->string('profilePicture', 1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('users', ['name', 'surname', 'birthDate', 'country', 'city', 'homeAdress', 'phone', 'profilePicture']);
    }
};
