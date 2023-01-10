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
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('guest_created')->nullable()->default(false);
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('guest_created')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('guest_created');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('guest_created');
        });
    }
};
