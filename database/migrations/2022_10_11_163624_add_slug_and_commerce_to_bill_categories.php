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
        Schema::table('bill_categories', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('shop_name')->nullable(); // How to display to shop
            $table->boolean('shop_show')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('shop_name');
            $table->dropColumn('show_show');
        });
    }
};
