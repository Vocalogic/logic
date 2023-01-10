<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hardware_orders', function (Blueprint $table) {
            $table->string('ship_contact');
            $table->string('ship_company');
            $table->string('ship_address');
            $table->string('ship_address2')->nullable();
            $table->string('ship_csz');
            $table->string('ship_notes', 1024)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hardware_orders', function (Blueprint $table) {
            $table->dropColumn('ship_contact');
            $table->dropColumn('ship_company');
            $table->dropColumn('ship_address');
            $table->dropColumn('ship_address2');
            $table->dropColumn('ship_csz');
            $table->dropColumn('ship_notes');
        });
    }
};
