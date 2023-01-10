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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('s3_key')->nullable();
            $table->string('s3_secret')->nullable();
            $table->string('s3_region')->nullable();
            $table->string('s3_bucket')->nullable();
            $table->string('s3_url')->nullable();
            $table->string('s3_endpoint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('s3_key');
            $table->dropColumn('s3_secret');
            $table->dropColumn('s3_region');
            $table->dropColumn('s3_bucket');
            $table->dropColumn('s3_url');
            $table->dropColumn('s3_endpoint');
        });
    }
};
