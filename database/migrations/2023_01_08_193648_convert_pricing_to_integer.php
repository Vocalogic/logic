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
        // First multiply all items by 100 to get the integer value before converting.
        foreach(\App\Models\BillItem::all() as $item)
        {
            $item->update([
               'nrc' => $item->nrc * 100,
               'mrc' => $item->mrc * 100,
               'ex_capex' => $item->ex_capex * 100,
               'ex_opex' => $item->ex_opex * 100,
               'msrp' => $item->msrp * 100,
               'reservation_price' => $item->reservation_price * 100,
               'min_price' => $item->min_price * 100,
               'max_price' => $item->max_price * 100
            ]);
        }

        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `nrc` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `mrc` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `ex_capex` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `ex_opex` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `msrp` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `reservation_price` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `min_price` INT");
        \Illuminate\Support\Facades\DB::statement("ALTER table `bill_items` MODIFY COLUMN `max_price` INT");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
