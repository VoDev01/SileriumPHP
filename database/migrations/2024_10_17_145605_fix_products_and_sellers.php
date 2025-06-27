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
        if(!Schema::hasColumns('sellers', ['organization_email', 'organization_type']))
        {
            Schema::table('sellers', function (Blueprint $table) {
                $table->renameColumn('email', 'organization_email');
                $table->renameColumn('type', 'organization_type');
            });
        }
        if(Schema::hasColumn('products', 'stockAmount'))
        {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stockAmount');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumns('sellers', ['organization_email', 'organization_type']))
        {
            Schema::table('sellers', function (Blueprint $table) {
                $table->renameColumn('organization_email', 'email');
                $table->renameColumn('organization_type', 'type');
            });
        }
        if(!Schema::hasColumn('products', 'stockAmount'))
        {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stockAmount');
            });
        }
    }
};
