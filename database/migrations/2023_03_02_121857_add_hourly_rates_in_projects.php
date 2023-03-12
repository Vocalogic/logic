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
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('project_hourly_rate')->nullable();
        });

        Schema::table('project_categories', function (Blueprint $table) {
            $table->integer('category_hourly_rate')->nullable();
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            $table->integer('task_hourly_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_hourly_rate');
        });

        Schema::table('project_categories', function (Blueprint $table) {
            $table->dropColumn('category_hourly_rate');
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            $table->dropColumn('task_hourly_rate');
        });
    }
};
