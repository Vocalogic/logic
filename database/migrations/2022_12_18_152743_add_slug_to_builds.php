<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_builds', function (Blueprint $table) {
            $table->string('slug');
        });

        Schema::table('package_sections', function($t)
        {
           $t->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_builds', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('package_sections', function($t)
        {
            $t->dropColumn('description');
        });
    }
};
