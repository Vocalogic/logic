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
        Schema::table('quote_item_addons', function ($t) {
            $t->integer('quote_item_id');
            $t->integer('addon_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_item_addons', function ($t) {
            $t->dropColumn('quote_item_id');
            $t->dropColumn('addon_id');
        });
    }
};
