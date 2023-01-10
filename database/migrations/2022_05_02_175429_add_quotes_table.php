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
        Schema::create('quotes', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');
            $t->string('hash');
            $t->integer('lead_id')->nullable()->defualt(0);
            $t->integer('account_id')->nullable()->default(0);
            $t->string('status')->nullable()->default('Draft');
            $t->boolean('archived')->nullable()->default(0);
            $t->boolean('preferred')->nullable()->default(0);
            $t->timestamp('sent_on')->nullable();
            $t->timestamp('expires_on')->nullable();
            $t->string('notes', 2048)->nullable();
            $t->integer('term')->nullable()->default(0);

        });

        Schema::create('quote_items', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('quote_id');
            $t->integer('item_id'); // Product or Service
            $t->double('price')->nullable()->default(0);
            $t->double('qty')->nullable()->default(1.0);
            $t->string('notes', 1024)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quote_items');
        Schema::drop('quotes');
    }
};
