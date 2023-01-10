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
        Schema::create('coupons', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('coupon'); // 50-OFF
            $t->string('name'); // 50$ off your first order!
            $t->boolean('total_invoice')->nullable()->default(false); // Apply to entire invoice? Not product spec.
            $t->timestamp('start')->nullable();
            $t->timestamp('end')->nullable();
            $t->text('details')->nullable();
            $t->double('dollars_off')->nullable(); // entire invoice only
            $t->double('dollar_spend_required')->nullable(); // For total invoice
            $t->integer('remaining')->nullable()->default(-1); // Default unlimited.
            $t->boolean('new_accounts_only')->nullable()->default(false);
            $t->integer('percent_off')->nullable(); // only applies to entire invoice
        });

         // Assign a coupon to applicable product.
        Schema::create('bill_item_coupons', function($t)
        {
            $t->increments('id');
            $t->timestamps();
            $t->integer('bill_item_id');
            $t->integer('coupon_id');
            $t->integer('max_qty')->nullable()->default(1);
            $t->integer('min_qty')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('bill_item_coupons');
    }
};
