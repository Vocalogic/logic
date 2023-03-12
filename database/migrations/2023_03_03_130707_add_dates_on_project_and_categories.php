<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function ($t) {
            $t->timestamp('start_date')->nullable();
            $t->timestamp('end_date')->nullable();
        });

        Schema::table('project_categories', function($t)
        {
            $t->timestamp('start_date')->nullable();
            $t->timestamp('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function ($t) {
            $t->dropColumn('start_date');
            $t->dropColumn('end_date');
        });

        Schema::table('project_categories', function($t)
        {
            $t->dropColumn('start_date');
            $t->dropColumn('end_date');
        });
    }
};
