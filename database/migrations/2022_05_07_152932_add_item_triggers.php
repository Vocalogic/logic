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
            $t->boolean('creates_lnp')->nullable()->default(false);
            $t->boolean('creates_hw')->nullable()->default(false);
            $t->boolean('creates_pbx')->nullable()->default(false);
            $t->boolean('creates_visit')->nullable()->default(false);
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
            $t->dropColumn('creates_lnp');
            $t->dropColumn('creates_hw');
            $t->dropColumn('creates_pbx');
            $t->dropColumn('creates_visit');
        });
    }
};
