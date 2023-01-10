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
            $t->boolean('reservation_mode')->nullable()->default(false);
            $t->double('reservation_price')->nullable();
            $t->text('reservation_details')->nullable();
            $t->string('reservation_time')->nullable();
            $t->string('reservation_refund', 2048)->nullable();
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
            $t->dropColumn('reservation_mode');
            $t->dropColumn('reservation_price');
            $t->dropColumn('reservation_details');
            $t->dropColumn('reservation_time');
            $t->dropColumn('reservation_refund');
        });
    }
};
