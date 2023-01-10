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
        Schema::create('commissions', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id'); // Account to be commissioned
            $t->integer('user_id');    // Sold by
            $t->integer('invoice_id'); // Which invoice
            $t->string('status')->nullable()->default('Draft');
            $t->date('scheduled_on')->nullable(); // When invoice is paid when scheduled to be paid out
            $t->double('amount');                 // Commission Amount
            $t->boolean('active')->nullable()->default(true);
        });

        Schema::table('accounts', function ($t) {
            $t->boolean('spiffed')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('commissions');
        Schema::table('accounts', function ($t) {
            $t->dropColumn('spiffed');
        });
    }
};
