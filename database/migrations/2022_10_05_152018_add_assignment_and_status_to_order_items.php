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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('status')->nullable()->default('Pending');
            $table->integer('assigned_id')->nullable();
        });
        \Illuminate\Support\Facades\DB::statement('alter table order_items CHANGE description description VARCHAR(2048);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('assigned_id');
        });
    }
};
