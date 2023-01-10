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
        Schema::table('bill_item_meta', function ($t) {
            $t->boolean('customer_viewable')->nullable()->default(true);
            $t->string('description', 1024)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_item_meta', function ($t) {
            $t->dropColumn('customer_viewable');
            $t->dropColumn('description');
        });
    }
};
