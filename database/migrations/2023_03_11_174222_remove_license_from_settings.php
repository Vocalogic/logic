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
       $setting = \App\Models\Setting::where('ident', 'brand.license')->first();
       if ($setting) $setting->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
