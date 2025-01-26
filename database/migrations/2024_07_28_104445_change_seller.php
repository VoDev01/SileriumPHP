<?php

use App\Enum\TaxSystem;
use App\Enum\OrganizationType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->renameColumn('img', 'logo');
            $table->renameColumn('name', 'nickname');
            $table->enum('type', [OrganizationType::AO->value, OrganizationType::OOO->value, OrganizationType::IE->value]);
            $table->enum('tax_system', [TaxSystem::USN->value, TaxSystem::OSNO->value, TaxSystem::ESHN->value]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->renameColumn('logo', 'img');
            $table->renameColumn('nickname', 'name');
            $table->dropColumn('type');
            $table->dropColumn('tax_system');
        });
    }
};
