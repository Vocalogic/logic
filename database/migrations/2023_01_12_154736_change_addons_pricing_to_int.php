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
        foreach(\App\Models\AddonOption::all() as $item)
        {
            $item->update([
                'price' => $item->price * 100,
            ]);

        }
        \Illuminate\Support\Facades\DB::statement("ALTER table `addon_options` MODIFY COLUMN `price` INT");

        // Convert Quote Item Addons as well
        foreach(\App\Models\QuoteItemAddon::all() as $item)
        {
            $item->update([
                'price' => $item->price * 100,
            ]);
        }
        \Illuminate\Support\Facades\DB::statement("ALTER table `quote_item_addons` MODIFY COLUMN `price` INT");

        // And for Account Addons
        foreach(\App\Models\AccountAddon::all() as $item)
        {
            $item->update([
                'price' => $item->price * 100,
            ]);
        }
        \Illuminate\Support\Facades\DB::statement("ALTER table `account_addons` MODIFY COLUMN `price` INT");
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
