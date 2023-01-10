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
        Schema::table('quote_items', function ($t) {
            $t->json('meta')->nullable();
        });

        Schema::table('account_items', function ($t) {
            $t->json('meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_items', function ($t) {
            $t->dropColumn('meta');
        });
        Schema::table('account_items', function ($t) {
            $t->dropColumn('meta');
        });
    }
};
