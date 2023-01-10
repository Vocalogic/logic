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
        Schema::create('user_tfas', function($t)
        {
           $t->increments('id');
           $t->timestamps();
           $t->integer('user_id');
           $t->string('ip');
           $t->timestamp('last_verification')->nullable();
           $t->timestamp('last_sent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tfas');
    }
};
