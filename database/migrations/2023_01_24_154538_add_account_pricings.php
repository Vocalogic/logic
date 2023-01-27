<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_pricings', function($t)
        {
           $t->increments('id');
           $t->timestamps();
           $t->integer('account_id');
           $t->integer('bill_item_id');
           $t->integer('price');
           $t->integer('price_children')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_pricings');
    }
};
