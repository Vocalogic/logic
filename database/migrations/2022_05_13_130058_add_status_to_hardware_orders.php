<?php

use App\Enums\Core\ShipmentStatus;
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
        Schema::table('hardware_orders', function (Blueprint $table) {
            $table->string('status')->nullable()->default(ShipmentStatus::Draft->value);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hardware_orders', function (Blueprint $table) {
           $table->dropColumn('status');
        });
    }
};
