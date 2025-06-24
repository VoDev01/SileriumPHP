<?php

use App\Models\Seller;
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
        if(Schema::hasTable('sellers_products'))
            Schema::drop('sellers_products');
        if(!Schema::hasColumn('products', 'productAmount'))
            Schema::table('products', function(Blueprint $table){
                $table->integer('productAmount');
            });
        if(Schema::hasColumn('products', 'seller_id'))
            Schema::table('products', function(Blueprint $table){
                $table->dropColumn('seller_id');
            });
        Schema::table('products', function(Blueprint $table){
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sellers_products', function(Blueprint $table){
            $table->foreignId('seller_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('productAmount');
        });
        Schema::table('products', function(Blueprint $table){
            $table->dropForeign(['seller_id']);    
        });

        Schema::table('products', function(Blueprint $table){
            $table->dropColumn('seller_id');
            $table->dropColumn('productAmount');
        });
    }
};
