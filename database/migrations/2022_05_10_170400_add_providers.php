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
        Schema::create('providers', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name'); // Dialpath
            $t->string('website'); // www.dialpath.com
            $t->string('logo')->nullable();
            $t->boolean('enabled')->nullable()->default(false);
            $t->string('endpoint'); // API Endpoint
            $t->string('client_id')->nullable(); // oauth client id
            $t->string('client_secret')->nullable(); // oauth client secret
            $t->string('username')->nullable(); // NS Reseller Username
            $t->string('password')->nullable(); // NS Reseller password
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('providers');
    }
};
