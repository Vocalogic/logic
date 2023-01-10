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
        foreach(\App\Models\Transaction::all() as $item)
        {
            $item->update([
                'amount' => $item->amount * 100,
            ]);
        }

        \Illuminate\Support\Facades\DB::statement("ALTER table `transactions` MODIFY COLUMN `amount` INT");
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
