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
            $t->timestamp('requested_termination_date')->nullable();
            $t->string('requested_termination_reason', 2048)->nullable();
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
            $t->dropColumn('requested_termination_date');
            $t->dropColumn('requested_termination_reason');
        });
    }
};
