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
        Schema::create('partner_invoices', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('partner_id'); // Who this invoice is from.
            $t->string('hash');
            $t->string('status');
            $t->timestamp('paid_on')->nullable();
        });

        Schema::create('partner_invoice_items', function($t)
        {
            $t->increments('id');
            $t->timestamps();
            $t->integer('partner_invoice_id');
            $t->string('name'); // Account Name
            $t->string('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_invoice_items');
        Schema::dropIfExists('partner_invoices');
    }
};
