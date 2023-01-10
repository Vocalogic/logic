<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_items', function($t)
        {
           $t->integer('allowed_qty')->nullable();
           $t->string('allowed_type')->nullable();
           $t->double('allowed_overage')->nullable();
        });

        Schema::table('account_items', function($t)
        {
            $t->integer('allowed_qty')->nullable();
            $t->string('allowed_type')->nullable();
            $t->double('allowed_overage')->nullable();
        });

        Schema::table('quote_items', function($t)
        {
            $t->integer('allowed_qty')->nullable();
            $t->string('allowed_type')->nullable();
            $t->double('allowed_overage')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_items', function($t)
        {
            $t->dropColumn('allowed_qty');
            $t->dropColumn('allowed_type');
            $t->dropColumn('allowed_overage');
        });

        Schema::table('account_items', function($t)
        {
            $t->dropColumn('allowed_qty');
            $t->dropColumn('allowed_type');
            $t->dropColumn('allowed_overage');
        });

        Schema::table('quote_items', function($t)
        {
            $t->dropColumn('allowed_qty');
            $t->dropColumn('allowed_type');
            $t->dropColumn('allowed_overage');
        });

    }
};
