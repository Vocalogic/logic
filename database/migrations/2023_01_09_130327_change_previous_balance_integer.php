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
        foreach(\App\Models\Invoice::all() as $item)
        {
            $item->update([
                'previous_balance' => $item->previous_balance * 100,
            ]);

        }
        \Illuminate\Support\Facades\DB::statement("ALTER table `invoices` MODIFY COLUMN `previous_balance` INT");

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
