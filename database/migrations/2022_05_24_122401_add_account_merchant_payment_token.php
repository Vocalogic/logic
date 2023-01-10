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
           $t->string('merchant_payment_token')->nullable();
           $t->string('merchant_payment_type')->nullable();
           $t->string('merchant_payment_last4')->nullable();
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
            $t->dropColumn('merchant_payment_token');
            $t->dropColumn('merchant_payment_type');
            $t->dropColumn('merchant_payment_last4');
        });
    }
};
