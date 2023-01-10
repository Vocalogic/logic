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
        Schema::table('lo_logs', function (Blueprint $table) {
            $table->boolean('read')->nullable()->default(false);
            $table->string('title')->nullable();
            $table->string('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lo_logs', function (Blueprint $table) {
            $table->dropColumn('read');
            $table->dropColumn('title');
            $table->dropColumn('link');
        });
    }
};
