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
        Schema::create('settings', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('ident');                  // app.name
            $t->string('question');               // What is the App Name?
            $t->string('type');                   // input, select
            $t->string('default')->nullable();    // Default value when init
            $t->string('help', 1024);             // When entering your app name blah blah blah.
            $t->string('category');               // Core, Billing, etc.
            $t->string('value')->nullable();      // Current Value
            $t->string('opts', 1024)->nullable(); // Options If a select
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
};
