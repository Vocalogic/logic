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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_agent')->nullable()->default(0);
            $table->double('agent_comm_mrc')->nullable()->default(0);    // 17% MRR?
            $table->integer('agent_comm_spiff')->nullable()->default(0); // 1 x MRR?

            $table->double('goal_self_monthly')->nullable()->default(0);  // Self defined executed
            $table->double('goal_self_quarterly')->nullable()->default(0);

            $table->double('goal_monthly')->nullable()->default(0); // Executed order goal
            $table->double('goal_quarterly')->nullable()->default(0);

            $table->double('goal_f_monthly')->nullable()->default(0); // Forecasted order goal
            $table->double('goal_f_quarterly')->nullable()->default(0);



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_agent');
            $table->dropColumn('agent_comm_mrc');
            $table->dropColumn('agent_comm_spiff');
            $table->dropColumn('goal_monthly');
            $table->dropColumn('goal_quarterly');
            $table->dropColumn('goal_self_monthly');
            $table->dropColumn('goal_self_quarterly');
            $table->dropColumn('goal_f_monthly');
            $table->dropColumn('goal_f_quarterly');

        });
    }
};
