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
        Schema::create('provisionings', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('account_id');
            $t->integer('order_id')->nullable(); // May be attached to an initial order.
            $t->timestamp('completed_on')->nullable();
            $t->boolean('active')->nullable()->default(1);


            $t->integer('complete_extensions_id')->nullable();
            $t->integer('complete_dids_id')->nullable();
            $t->integer('complete_e911_id')->nullable();
            $t->integer('complete_install_id')->nullable();

            $t->integer('extension_count')->nullable()->default(0);
            $t->integer('did_count')->nullable()->default(0);

            $t->text('extensions')->nullable(); // Json extension list
            $t->text('dids')->nullable();       // json did list

            $t->text('e911_address')->nullable();   // primary e911
            $t->text('open_hours')->nullable();     // Explain when you are open.
            $t->text('operation_open')->nullable();
            $t->text('operation_closed')->nullable();


        });

        Schema::table('orders', function ($t) {
            $t->string('hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('provisionings');
        Schema::table('orders', function ($t) {
            $t->dropColumn('hash');
        });
    }
};
