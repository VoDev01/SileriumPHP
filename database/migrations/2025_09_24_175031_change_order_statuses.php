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
        Schema::table('orders', function (Blueprint $table)
        {
            $table->renameColumn('created_at', 'created_at');
            $table->renameColumn('address', 'address');
            $table->dropColumn('status');
        });
        Schema::table('orders', function (Blueprint $table)
        {
            $table->enum('status', ['ISSUING', 'PENDING', 'CACNCELLED', 'DELIVERY', 'RECEIVED', 'ASSEMBLING', 'REFUND', 'PARTIAL_REFUND'])->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table)
        {
            $table->renameColumn('created_at', 'created_at');
            $table->renameColumn('address', 'address');
            $table->dropColumn('status');
        });
        Schema::table('orders', function (Blueprint $table)
        {
            $table->enum('status', ['ISSUING','PENDING','CLOSED','DELIVERY','RECEIVED'])->after('address');
        });
    }
};
