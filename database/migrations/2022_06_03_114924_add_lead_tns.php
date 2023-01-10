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
        Schema::create('lead_tns', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('lead_id');
            $t->string('number');
            $t->string('type')->nullable();
            $t->string('agency')->nullable();
            $t->string('description')->nullable();
            $t->boolean('is_btn')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lead_tns');
    }
};
