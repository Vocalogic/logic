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
            $t->dropColumn('count_seats');
            $t->dropColumn('count_tns');
            $t->dropColumn('count_localmin');
            $t->dropColumn('count_tfmin');
            $t->dropColumn('allowed_qty');
            $t->dropColumn('allowed_type');
            $t->dropColumn('allowed_overage');
        });

        Schema::table('quote_items', function ($t) {
            $t->dropColumn('allowed_qty');
            $t->dropColumn('allowed_type');
            $t->dropColumn('allowed_overage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
