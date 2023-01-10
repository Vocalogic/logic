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
        Schema::create('file_categories', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');
            $t->string('type');
            $t->boolean('default_public')->nullable()->default(0);
            $t->boolean('locked')->nullable()->default(0);
        });

        Schema::table('lo_files', function ($t) {
            $t->integer('file_category_id')->nullable();
        });

        \App\Models\FileCategory::create([
           'name' => "Unsorted Documents",
           'locked' => true,
           'type' => 'DOCUMENT',
           'default_public' => false,
        ]);

        \App\Models\FileCategory::create([
            'name' => "Signed Contracts",
            'locked' => true,
            'type' => 'DOCUMENT',
            'default_public' => false,
        ]);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_categories');
        Schema::table('lo_files', function ($t) {
            $t->dropColumn('file_category_id');
        });
    }
};
