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
        Schema::table('orders', function ($t) {
            $t->dropColumn('quote_id'); // Orders cannot be done from a quote now.
        });

        Schema::create('order_items', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->boolean('product')->nullable()->default(0); // Ordering a service or product
            $t->integer('bill_item_id')->nullable(); // Could be freeform
            $t->string('code')->nullable();
            $t->string('name')->nullable();
            $t->string('description')->nullable();
            $t->double('qty');
            $t->double('price');
            $t->integer('shipment_id')->nullable(); // If product is shipped. It has tracking, etc.
        });

        Schema::create('order_notes', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('order_id');
            $t->integer('order_item_id');
            $t->integer('user_id');         // Who made the note
            $t->string('note', 1024);       // The note made.
        });

        \Illuminate\Support\Facades\DB::statement('RENAME TABLE hardware_orders TO shipments');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function ($t) {
            $t->integer('quote_id');
        });

        Schema::drop('order_items');
        Schema::drop('order_notes');
        \Illuminate\Support\Facades\DB::statement('RENAME TABLE shipments TO hardware_orders');

    }
};
