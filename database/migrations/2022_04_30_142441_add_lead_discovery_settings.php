<?php

use App\Models\Discovery;
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
        Schema::create('discoveries', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('question');
            $t->string('type');
            $t->string('opts')->nullable();
        });

        // Add some seeder values
        (new Discovery)->create([
            'question' => "Current Provider",
            'type'     => 'input'
        ]);
        (new Discovery)->create([
            'question' => "Current Bill Amount",
            'type'     => 'input'
        ]);
        (new Discovery)->create([
            'question' => "On-Site Serviced",
            'type'     => 'select',
            'opts'     => 'No,Yes'
        ]);

        Schema::create('lead_discoveries', function($t)
        {
           $t->increments('id');
           $t->timestamps();
           $t->integer('discovery_id');
           $t->integer('lead_id');
           $t->string('value')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discoveries');
        Schema::drop('lead_discoveries');
    }
};
