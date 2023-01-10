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
        Schema::create('partners', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('code'); // Remote partner code
            $t->string('name')->nullable(); // Partner Name
            $t->string('partner_host')->nullable(); // https://my.vocalogic.com
            $t->double('commission_in_mrc')->nullable(); // What we receive
            $t->double('commission_in_spiff')->nullable();
            $t->double('commission_out_mrc')->nullable(); // What we pay out
            $t->double('commission_out_spiff')->nullable();
            $t->timestamp('invited_on')->nullable();
            $t->timestamp('accepted_on')->nullable();
            $t->text('description')->nullable(); // Send certain types of leads to this person.
            $t->boolean('active')->nullable()->default(true);
            $t->string('status')->nullable()->default('Pending Invitation');
            $t->integer('net_days')->nullable()->default(30);
            $t->boolean('originated_self')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('partners');
    }
};
