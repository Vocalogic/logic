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
        Schema::table('bill_items', function (Blueprint $table) {
            $table->integer('on_hand')->nullable()->default(0);
            $table->boolean('track_qty')->nullable()->default(false);
            $table->boolean('allow_backorder')->nullable()->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropColumn('on_hand');
            $table->dropColumn('track_qty');
            $table->dropColumn('allow_backorder');
        });
    }
};
