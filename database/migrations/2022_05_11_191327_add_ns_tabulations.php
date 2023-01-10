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
        Schema::table('bill_items', function ($t) {
            $t->boolean('count_seats')->nullable()->default(false);
            $t->boolean('count_tns')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_items', function ($t) {
            $t->dropColumn('count_seats');
            $t->dropColumn('count_tns');
        });

    }
};
