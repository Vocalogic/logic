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
        Schema::create('tax_locations', function ($t) {
            $t->id();
            $t->timestamps();
            $t->string('location'); // State, etc.
            $t->double('rate'); // Tax rate.. 5.5% etc.
        });

        Schema::table('bill_items', function($t)
        {
           $t->boolean('taxable')->nullable()->default(false);
        });

        Schema::table('accounts', function($t)
        {
            $t->boolean('taxable')->nullable()->default(true);
        });

        Schema::table('quotes', function($t)
        {
           $t->integer('tax')->nullable()->default(0);
        });
        Schema::table('invoices', function($t)
        {
            $t->integer('tax')->nullable()->default(0);
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
            $t->dropColumn('taxable');
        });

        Schema::table('accounts', function($t)
        {
            $t->dropColumn('taxable');
        });

        Schema::table('quotes', function($t)
        {
            $t->dropColumn('tax');
        });
        Schema::table('invoices', function($t)
        {
            $t->dropColumn('tax');
        });

        Schema::dropIfExists('tax_locations');
    }
};
