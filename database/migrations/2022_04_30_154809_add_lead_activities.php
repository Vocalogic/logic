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
        Schema::create('activities', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('type');                  // Lead, etc.
            $t->integer('refid');                // Referencing Id
            $t->integer('user_id');              // Who made the post
            $t->integer('image_id')->nullable(); // If uploaded image
            $t->timestamp('event')->nullable();  // in case of event
            $t->string('post', 2048);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activities');
    }
};
