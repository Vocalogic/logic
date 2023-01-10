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
        Schema::create('meetings', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name'); // Cut some Grass
            $t->integer('account_id')->nullable();
            $t->integer('lead_id')->nullable();
            $t->timestamp('starts')->nullable();
            $t->timestamp('ends')->nullable();
            $t->timestamp('sent_on')->nullable();
            $t->timestamp('confirmed_on')->nullable();
            $t->text('body');
            $t->string('type');
            $t->string('remote_id', 1024); // For any calendar integration.
            $t->boolean('recurring')->default(false);
            $t->string('recurring_method')->nullable(); // Monthly, Quarterly
            $t->integer('parent_id')->nullable(); // Events spawned from a repeating event.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meetings');
    }
};
