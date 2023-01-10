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
        Schema::create('account_items', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id');
            $t->integer('bill_item_id');
            $t->string('description', 1024);
            $t->double('price');
            $t->integer('qty');
            $t->integer('quote_id')->nullable();
        });

        Schema::create('account_overrides', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('bill_item_id');
            $t->integer('account_id');
            $t->double('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('account_items');
        Schema::drop('account_overrides');
    }
};
