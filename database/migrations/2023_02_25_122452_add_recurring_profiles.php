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
        Schema::create('recurring_profiles', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('account_id');
            $t->string('name')->nullable();
            $t->string('po')->nullable();
            $t->timestamp('next_bill')->nullable();
            $t->integer('bills_on')->nullable();
        });

        Schema::table('account_items', function ($t) {
            $t->integer('recurring_profile_id')->nullable();
        });

        Schema::table('invoices', function($t)
        {
           $t->string('title')->nullable(); // For recurring profile name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_profiles');
        Schema::table('account_items', function ($t) {
            $t->dropColumn('recurring_profile_id');
        });

        Schema::table('invoices', function($t)
        {
            $t->dropColumn('title');
        });
    }
};
