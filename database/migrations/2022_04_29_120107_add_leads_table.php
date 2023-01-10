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
        Schema::create('leads', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('type');                 // Type of lead. Phones, Something, whatever
            $t->string('company')->nullable();  // Company name
            $t->string('contact');              // Person name
            $t->string('email')->nullable();    // Email
            $t->string('phone')->nullable();    // Phone number
            $t->string('title')->nullable();    // Title.
            $t->string('stage')->nullable();    // Stage (new, discovery, etc)
            $t->integer('logo_id')->nullable(); // Company logo (for proposals, etc)
            $t->boolean('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('leads');
    }
};
