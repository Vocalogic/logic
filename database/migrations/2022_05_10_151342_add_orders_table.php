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
        Schema::create('orders', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('quote_id');   // Which quote
            $t->string('name');        // Order for 3 Phones
            $t->integer('account_id'); // Which account.
            $t->boolean('active')->nullable()->default(true);
            $t->timestamp('completed_on')->nullable();
            $t->integer('user_id')->nullable();         // Who is it assigned to?
            $t->string('ticket_id')->nullable(); // If we are bound to a support integration
        });

        Schema::create('lnp_orders', function ($t) {
            $t->increments('id');
            $t->integer('order_id');                           // Needs to tie to a master order.
            $t->string('status');                              // Draft, Pending LOA, Processing, etc.
            $t->integer('user_id')->nullable();                // Who is this lnp order managed by.
            $t->integer('provider_id');                        // Which provider is this assigned to. (also where to email)
            $t->timestamp('submitted_on')->nullable();         // When submitted to vendor
            $t->timestamp('completed_on')->nullable();         // When order completed.
            $t->timestamp('customer_sent_on')->nullable();     // When customer signed.
            $t->timestamp('signed_on')->nullable();            // When customer signed.
            $t->date('ddd')->nullable();                       // Date Desired
            $t->date('foc')->nullable();                       // When FOC is done
            $t->string('rejection_reason')->nullable();        // For dashboard
            $t->string('hash');                                // So we can send the request to the customer easily.
            $t->string('p_company')->nullable();
            $t->string('p_contact')->nullable();
            $t->string('p_provider')->nullable();
            $t->string('p_address')->nullable();
            $t->string('p_city')->nullable();
            $t->string('p_state')->nullable();
            $t->string('p_zip')->nullable();
            $t->string('p_btn')->nullable();
            $t->string('p_numbers', 2048)->nullable();
            $t->text('p_signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lnp_orders');
        Schema::drop('orders');
    }
};
