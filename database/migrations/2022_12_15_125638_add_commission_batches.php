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
        Schema::create('commission_batches', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('user_id');                       // Which user is this batch for?
            $t->string('transaction_detail')->nullable(); // Check #34123
            $t->timestamp('paid_on')->nullable();
            $t->text('notes')->nullable();
            $t->integer('paid_by')->nullable();
            $t->text('paid_notes')->nullable();
        });

        Schema::table('commissions', function ($t) {
            $t->integer('commission_batch_id')->nullable();
            $t->string('edit_note', 2048)->nullable();  // Updated Amount for reason.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions', function ($t) {
            $t->dropColumn('commission_batch_id');
            $t->dropColumn('edit_note');
        });
        Schema::dropIfExists('commission_batches');
    }
};
