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
        Schema::create('lo_logs', function($t)
        {
            $t->increments('id');
            $t->timestamps();
            $t->integer('user_id')->nullable()->default(0);
            $t->integer('account_id')->nullable();
            $t->string('category')->nullable()->default('SYSTEM');
            $t->string('type')->nullable()->default('INFO');
            $t->string('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lo_logs');
    }
};
