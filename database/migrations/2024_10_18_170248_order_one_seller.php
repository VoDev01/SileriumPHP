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
        Schema::drop('sellers_orders');
        Schema::table('orders', function(Blueprint $table){
            $table->foreignId('seller_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table){
            $table->dropConstrainedForeignId('seller_id');
        });
        Schema::create('sellers_orders', function (Blueprint $table) {
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('order_id')->constrained();
            $table->double('totalPrice', 100, 2);
        });
    }
};
