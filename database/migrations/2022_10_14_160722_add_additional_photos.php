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
        Schema::table('bill_items', function ($t) {
            $t->integer('photo_2')->nullable();
            $t->integer('photo_3')->nullable();
            $t->integer('photo_4')->nullable();
            $t->integer('photo_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_items', function ($t) {
            $t->dropColumn('photo_2');
            $t->dropColumn('photo_3');
            $t->dropColumn('photo_4');
            $t->dropColumn('photo_5');
        });
    }
};
