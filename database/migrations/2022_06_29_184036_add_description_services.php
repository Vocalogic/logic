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
        // Actually we need to set qty to double here anyway..
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE account_items CHANGE qty qty DOUBLE');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE invoice_items CHANGE qty qty DOUBLE');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
