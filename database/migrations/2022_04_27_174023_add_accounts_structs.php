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
        Schema::create('accounts', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');
            $t->string('address')->nullable();
            $t->string('address2')->nullable();
            $t->string('city')->nullable();
            $t->string('state')->nullable();
            $t->string('postcode')->nullable();
            $t->string('country')->nullable();
            $t->string('phone')->nullable();
            $t->uuid('uuid')->nullable();
            $t->boolean('active')->default(1);
        });

        Schema::table('users', function ($t) {
            $t->integer('account_id')->nullable();
            $t->string('acl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');
        Schema::table('users', function ($t) {
            $t->dropColumn('account_id');
            $t->dropColumn('acl');
        });
    }
};
