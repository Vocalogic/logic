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
        Schema::create('hardware_orders', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('order_id');
            $t->integer('vendor_id');                  // Who is getting the order
            $t->integer('vendor_invoice')->nullable(); // Uploaded Invoice
            $t->double('vendor_sub')->nullable();      // SubTotal on invoice
            $t->double('vendor_shipping')->nullable(); // Shipping cost on invoice
            $t->double('vendor_total')->nullable();    // Total on invoice

            $t->timestamp('submitted_on')->nullable();
            $t->timestamp('shipped_on')->nullable();
            $t->string('tracking')->nullable(); // Tracking information.
            $t->date('expected_arrival')->nullable();
        });

        Schema::create('hardware_order_items', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('hardware_order_id');
            $t->integer('bill_item_id'); // What product item are we ordering?
            $t->integer('qty');
            $t->string('notes', 1024)->nullable(); // Notes to vendor.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hardware_order_items');
        Schema::drop('hardware_orders');
    }
};
