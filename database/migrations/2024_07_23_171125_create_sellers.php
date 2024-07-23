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
        Schema::create('sellers', function (Blueprint $table) {
            $table->ulid('ulid');
            $table->primary('ulid');
            $table->string('name', 50);
            $table->string('organization_name', 75);
            $table->boolean('verified')->default(false);
            $table->float('rating', 2, 1)->default('1.0');
            $table->string('img', 1000)->nullable();
            $table->string('email', 100);
            $table->boolean('email_verified')->default(false);
        });
        DB::unprepared("ALTER TABLE sellers ADD id BIGINT(20);
        ALTER TABLE sellers ADD CONSTRAINT sellers_id UNIQUE(id);
        ALTER TABLE sellers MODIFY id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT AFTER ulid;
        ");
        Schema::create('sellers_users', function (Blueprint $table) {
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('user_id')->constrained();
        });
        if (Schema::hasColumn('products', 'stock_amount')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stock_amount');
            });
        }
        if (!Schema::hasColumn('orders_products', 'total_price')) {
            Schema::table('orders_products', function (Blueprint $table) {
                $table->float('total_price', 9, 2);
            });
        }
        Schema::create('sellers_products', function(Blueprint $table) {
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('product_amount');
        });
        Schema::create('sellers_orders', function (Blueprint $table) {
            $table->foreignId('sellers_id')->constrained();
            $table->foreignUlid('orders_id')->references('ulid')->on('orders');
            $table->float('total_price', 12, 2);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('sellers_users')) {
            
            Schema::table('sellers_users', function (Blueprint $table) {
                $table->dropForeign(['seller_id']);
                $table->dropForeign(['user_id']);
            });
        }
        if (Schema::hasTable('sellers_products')) {
            
            Schema::table('sellers_products', function (Blueprint $table) {
                $table->dropForeign(['seller_id']);
                $table->dropForeign(['product_id']);
            });
        }
        if (Schema::hasTable('sellers_orders')) {
            
            Schema::table('sellers_orders', function (Blueprint $table) {
                $table->dropForeign(['sellers_id']);
                $table->dropForeign(['orders_id']);
            });
        }
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('sellers_users');
        Schema::dropIfExists('sellers_products');
        Schema::dropIfExists('sellers_orders');
        if (!Schema::hasColumn('products', 'stock_amount')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stock_amount');
            });
        }
        if (Schema::hasColumn('orders_products', 'total_price')) {
            Schema::table('orders_products', function (Blueprint $table) {
                $table->dropColumn('total_price');
            });
        }
    }
};
