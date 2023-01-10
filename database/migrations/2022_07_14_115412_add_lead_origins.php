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
        Schema::create('lead_origins', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');
            $t->softDeletes();
        });

        Schema::table('leads', function ($t) {
            $t->integer('lead_origin_id')->nullable();
            $t->string('lead_origin_detail', 1024)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lead_origins');
        Schema::table('leads', function ($t) {
            $t->dropColumn('lead_origin_id');
            $t->dropColumn('lead_origin_detail');
        });
    }
};
