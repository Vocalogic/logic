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
        Schema::create('vendors', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');                    // Teledynamics
            $t->string('rep_name')->nullable();    // Who is the rep
            $t->string('rep_email')->nullable();   // Email of Rep
            $t->string('rep_phone')->nullable();   // Rep Phone
            $t->string('order_email')->nullable(); // Where are orders sent?
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendors');
    }
};
