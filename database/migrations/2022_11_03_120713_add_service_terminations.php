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
        Schema::table('account_items', function ($t) {
            $t->date('terminate_on')->nullable();
            $t->date('suspend_on')->nullable();
            $t->string('terminate_reason')->nullable();
            $t->string('suspend_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_items', function ($t) {
            $t->dropColumn('terminate_on');
            $t->dropColumn('suspend_on');
            $t->dropColumn('terminate_reason');
            $t->dropColumn('suspend_reason');
        });
    }
};
