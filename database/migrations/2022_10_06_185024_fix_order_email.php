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
        \App\Models\EmailTemplate::where('ident', 'account.order')->update([
           'module' => null,
           'subject' => '[#{shipment.id}] New {setting.brand-name} Order',
           'body' => 'A new {setting.brand-name} order has been generated. Please see attached PDF for reference.
Contents of the order and shipment information are as follows:

{shipment.vendor_detail}'
        ]);
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
