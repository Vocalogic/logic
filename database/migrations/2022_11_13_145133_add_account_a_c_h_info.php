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
        Schema::table('accounts', function ($t) {
            $t->string('merchant_ach_aba')->nullable();
            $t->string('merchant_ach_account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function($t)
        {
           $t->dropColumn('merchant_ach_aba');
           $t->dropColumn('merchant_ach_account');
        });
    }
};
