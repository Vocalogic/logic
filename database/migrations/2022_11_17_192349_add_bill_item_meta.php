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
        Schema::create('bill_item_meta', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('bill_item_id');
            $t->string('item');                                         // Number to Port
            $t->string('answer_type')->nullable()->default('input');    // Input/Select/Textarea
            $t->string('opts', 2048)->nullable();                       // Yes,No
            $t->boolean('required_sale')->nullable()->default(false);   // Require this during quoting or carting
            $t->boolean('per_qty')->nullable()->default(true);          // Entry per qty or just 1.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_item_meta');
    }
};
