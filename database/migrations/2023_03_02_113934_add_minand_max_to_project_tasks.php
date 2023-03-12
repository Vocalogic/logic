<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_tasks', function (Blueprint $table) {
            $table->dropColumn('est_hours');
            $table->double('est_hours_min')->nullable();
            $table->double('est_hours_max')->nullable();
            $table->string('bill_method')->default('Mixed'); // Static Price, Hourly, Mixed

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_tasks', function (Blueprint $table) {
            $table->double('est_hours')->nullable();
            $table->dropColumn('est_hours_min');
            $table->dropColumn('est_hours_max');
            $table->dropColumn('bill_method');
        });
    }
};
