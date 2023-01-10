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
            $t->date('next_bill_date')->nullable();
            $t->integer('remaining')->nullable();
            $t->double('interest_rate')->nullable();
            $t->string('frequency')->nullable()->default('MONTHLY');
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
            $t->dropColumn('next_bill_date');
            $t->dropColumn('remaining');
            $t->dropColumn('interest_rate');
            $t->dropColumn('frequency');
        });
    }
};
