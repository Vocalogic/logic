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
        Schema::table('account_addons', function ($t) {
            $t->integer('addon_option_id');
            $t->integer('addon_id');
            $t->integer('account_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_addons', function ($t) {
            $t->dropColumn('addon_option_id');
            $t->dropColumn('addon_id');
            $t->dropColumn('account_id');
        });
    }
};
