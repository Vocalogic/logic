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
        Schema::create('metrics', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id');              // Which account was this collected for
            $t->string('metric');                   // What metric type
            $t->double('value');                    // Value of metric
            $t->date('stamp');                      // The date of record. (not necessarily created_at)
            $t->string('detail', 2048)->nullable(); // Optional Detail Object for this metric type
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('metrics');
    }
};
