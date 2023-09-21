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
        Schema::create('products', function(Blueprint $table){
            $table->id();
            $table->string('name', 100);
            $table->string('description', 1000)->nullable(true);
            $table->float('priceRub', 10, 1);
            $table->integer('stockAmount');
            $table->boolean('available');
            $table->foreignId('subcategory_id')->constrained('subcategories');
            $table->foreignId('specification_id')->constrained('products_specs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
};
