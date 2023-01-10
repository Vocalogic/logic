<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function($t)
        {
           $t->double('account_credit')->nullable()->default(0.0);
           $t->string('account_credit_reason')->nullable();
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
            $t->dropColumn('account_credit');
            $t->dropColumn('account_credit_reason');
        });
    }
};
