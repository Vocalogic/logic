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
        Schema::create('tax_collections', function($t)
        {
           $t->id();
           $t->timestamps();
           $t->integer('invoice_id');
           $t->integer('tax_location_id');
           $t->integer('amount');
           $t->integer('tax_batch_id')->nullable();
        });

        Schema::create('tax_batches', function($t)
        {
           $t->id();
           $t->timestamps();
           $t->integer('tax_location_id');
           $t->timestamp('paid_on')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_collections');
        Schema::dropIfExists('tax_batches');
    }
};
