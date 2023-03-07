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
        $template = \App\Models\EmailTemplateCategory::where('name', 'Leads')->first();
        if (!$template)
        {
            $template = new \App\Models\EmailTemplateCategory();
            $template->id = 2;
            $template->name = "Leads";
            $template->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
