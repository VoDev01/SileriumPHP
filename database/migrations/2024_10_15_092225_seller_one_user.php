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
        if(Schema::hasTable('sellers_users'))
            Schema::drop('sellers_users');
        Schema::table('sellers', function (Blueprint $table) {
            if(!Schema::hasColumn('sellers', 'user_id'))
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sellers_users', function(Blueprint $table){
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
        if(Schema::hasColumn('sellers', 'user_id'))
        {
            Schema::table('sellers', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
