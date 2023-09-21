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
        Schema::create('orders', function(Blueprint $table){
            $table->uuid('id')->primary();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->float('totalPrice', 10, 1);
            $table->integer('orderAmount');
            $table->timestamp('orderDate')->useCurrent();
            $table->string('orderAdress', 300);
            $table->enum('orderStatus', ['Issuing', 'Opened', 'Pending', 'Closed', 'Delivery']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
};
