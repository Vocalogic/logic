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
        Schema::table('quotes', function (Blueprint $table) {
            $table->integer('coupon_id')->nullable(); // If a coupon was added track it here for comms
        });

        Schema::table('accounts', function($t)
        {
           $t->integer('affiliate_id')->nullable(); // when on an account we don't care about the coupon. just affid
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('coupon_id');
        });

        Schema::table('accounts', function($t)
        {
           $t->dropColumn('affiliate_id');
        });
    }
};
