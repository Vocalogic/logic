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
        Schema::table('quotes', function ($t) {
            $t->timestamp('activated_on')->nullable();
            $t->string('contract_name')->nullable();
            $t->string('contract_ip')->nullable();
            $t->text('signature')->nullable();
            $t->date('contract_expires')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function ($t) {
            $t->dropColumn('activated_on');
            $t->dropColumn('contract_name');
            $t->dropColumn('contract_ip');
            $t->dropColumn('signature');
            $t->dropColumn('contract_expires');
        });
    }
};
