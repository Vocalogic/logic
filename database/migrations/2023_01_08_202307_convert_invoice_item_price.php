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
        foreach(\App\Models\InvoiceItem::all() as $item)
        {
            $item->update([
                'price' => $item->price * 100,
            ]);
        }

        \Illuminate\Support\Facades\DB::statement("ALTER table `invoice_items` MODIFY COLUMN `price` INT");
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
