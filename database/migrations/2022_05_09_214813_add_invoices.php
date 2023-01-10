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
        Schema::create('invoices', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id');
            $t->string('status')->nullable()->default('Draft');
            $t->timestamp('due_on')->nullable();
            $t->timestamp('sent_on')->nullable();
            $t->timestamp('paid_on')->nullable();
        });

        Schema::create('invoice_items', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('invoice_id');
            $t->integer('bill_item_id')->nullable();
            $t->string('code');
            $t->string('name');
            $t->string('description', 1024);
            $t->integer('qty');
            $t->integer('price');
        });

        Schema::create('transactions', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('invoice_id');
            $t->integer('account_id');
            $t->double('amount');
            $t->string('local_transaction_id');
            $t->string('remote_transaction_id')->nullable();
            $t->string('details')->nullable();
            $t->string('method'); // Check, etc.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoices');
        Schema::drop('invoice_items');
        Schema::drop('transactions');
    }
};
