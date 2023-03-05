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
        Schema::table('project_task_entries', function($t)
        {
           $t->integer('invoice_id')->nullable();
            $t->boolean('billable')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_task_entries', function($t)
        {
            $t->dropColumn('invoice_id')->nullable();
            $t->dropColumn('billable');
        });
    }
};
