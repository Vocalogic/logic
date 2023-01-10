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
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropColumn('creates_lnp');
            $table->dropColumn('creates_hw');
            $table->dropColumn('creates_pbx');
            $table->dropColumn('creates_visit');

            // Add Item is shipped
            $table->boolean('is_shipped')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('creates_lnp');
            $table->boolean('creates_hw');
            $table->boolean('creates_pbx');
            $table->boolean('creates_visit');
            $table->dropColumn('is_shipped');
        });
    }
};
