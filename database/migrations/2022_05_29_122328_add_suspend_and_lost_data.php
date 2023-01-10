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

        Schema::table('leads', function ($t) {
            $t->timestamp('lost_on')->nullable();
            $t->timestamp('reactivate_on')->nullable();
            $t->string('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('leads', function ($t) {
            $t->dropColumn('lost_on');
            $t->dropColumn('reactivate_on');
            $t->dropColumn('reason');
        });

    }
};
