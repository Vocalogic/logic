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
        foreach(\App\Models\QuoteItem::all() as $item)
        {
            $item->update([
                'price' => $item->price * 100,
            ]);
        }

        \Illuminate\Support\Facades\DB::statement("ALTER table `quote_items` MODIFY COLUMN `price` INT");

        foreach(\App\Models\AccountItem::all() as $item)
        {
            $item->update([
                'price' => $item->price * 100,
            ]);
        }

        \Illuminate\Support\Facades\DB::statement("ALTER table `account_items` MODIFY COLUMN `price` INT");
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
