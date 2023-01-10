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
        Schema::create('call_recordings', function($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id');
            $t->string('status');
            $t->string('call_id');
            $t->timestamp('time_open')->nullable();
            $t->timestamp('time_close')->nullable();
            $t->integer('duration');
            $t->integer('size');
            $t->timestamp('time')->nullable();
            $t->text('url');
            $t->text('s3_url')->nullable();
            $t->string('cdr_id');
            $t->string('from')->nullable();
            $t->string('to')->nullable();
            $t->integer('ext')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('call_recordings');
    }
};
