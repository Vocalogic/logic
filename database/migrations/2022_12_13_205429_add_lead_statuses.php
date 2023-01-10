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

        Schema::create('lead_statuses', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');
            $t->boolean('locked')->nullable()->default(0);
            $t->boolean('is_lost')->nullable()->default(0);
            $t->boolean('is_won')->nullable()->default(0);
        });

        \App\Models\LeadStatus::create([
            'name'   => "New",
            'locked' => 1,
        ]);

        \App\Models\LeadStatus::create([
            'name'    => "Lost",
            'locked'  => 1,
            'is_lost' => 1
        ]);

        \App\Models\LeadStatus::create([
            'name'   => "Won",
            'locked' => 1,
            'is_won' => 1
        ]);

        $stage = \App\Models\Setting::where('ident', 'leads.stages')->first();
        if ($stage) $stage->delete();

        Schema::table('leads', function($t)
        {
            $t->dropColumn('stage');
            $t->integer('lead_status_id');
        });

        foreach(\App\Models\Lead::all() as $lead)
        {
            $lead->update(['lead_status_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_statuses');

        Schema::table('leads', function($t)
        {
            $t->string('stage');
            $t->dropColumn('lead_status_id');
        });
    }
};
