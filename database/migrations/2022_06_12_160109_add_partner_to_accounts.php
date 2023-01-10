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
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('is_partner')->nullable()->default(0);
            $table->boolean('partner_nrc')->nullable()->default(0); // Include NRC in commissions?
            $table->double('partner_commission_mrr')->nullable();
            $table->double('partner_commission_spiff')->nullable();
            $table->string('partner_commission_type')->nullable(); // SPIFF / MRR / BOTH
            $table->integer('partner_net_days')->nullable();           // How many days to pay out commission post payment
            $table->integer('partner_id')->nullable(); // Assigning an account to a partner sold it.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('is_partner');
            $table->dropColumn('partner_nrc');
            $table->dropColumn('partner_commission_mrr');
            $table->dropColumn('partner_commission_spiff');
            $table->dropColumn('partner_commission_type');
            $table->dropColumn('partner_net_days');
            $table->dropColumn('partner_id');
        });
    }
};
