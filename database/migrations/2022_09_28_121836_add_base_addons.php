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
        Schema::create('addons', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('bill_item_id');
            $t->string('name');                          // Select Handset
            $t->string('description', 1024)->nullable(); // Select a headset!
        });

        Schema::create('addon_options', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('addon_id');
            $t->string('name')->nullable();          // Custom Name
            $t->integer('bill_item_id')->nullable(); // What other product to select
            $t->double('price')->nullable();         // Set the price for this product if added on to this.
            $t->string('notes', 1024)->nullable();   // This could be a terms and conditions or a limitation, etc.
            $t->integer('max')->nullable()->default(1); // Max qty allowed
        });

        Schema::create('account_addons', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name')->nullable();             // Name of billitem or custom (in case removed)
            $t->integer('account_bill_item_id')->nullable();    // What other product to select if this is not found then use name.
            $t->double('price')->nullable();            // Set the price for this product if added on to this.
            $t->string('notes', 1024)->nullable();      // This could be a terms and conditions or a limitation, etc.
            $t->integer('qty')->nullable()->default(1); // Can have multiple.
        });

        Schema::create('quote_item_addons', function($t)
        {
            $t->increments('id');
            $t->timestamps();
            $t->string('name')->nullable();             // Name of item
            $t->integer('addon_option_id')->nullable();    // What other product to select if this is not found then use name.
            $t->double('price')->nullable();            // Set the price for this product if added on to this.
            $t->string('notes', 1024)->nullable();      // This could be a terms and conditions or a limitation, etc.
            $t->integer('qty')->nullable()->default(1); // Can have multiple.
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addons');
        Schema::drop('addon_options');
        Schema::drop('account_addons');
        Schema::drop('quote_item_addons');
    }
};
