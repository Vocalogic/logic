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
        Schema::create('lo_files', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('hash');                                     // The direct url link to download which will stream
            $t->string('filename');                                 // 12345.pdf - The name that this will get translated to.
            $t->string('real');                                     // unique id found local on filesystem.
            $t->string('description', 1024)->nullable();            // Optionally a description
            $t->string('location');                                 // Which storage bucket
            $t->string('type');                                     // Which LOFileType (invoice/quote, etc)
            $t->integer('ref_id');                                  // The ID on the model of the type to reference. Invoice id: 1, etc
            $t->boolean('admin_only')->nullable()->default(false);  // Only for admin accounts to view?
            $t->integer('account_id')->nullable();                  // Is for a particular account?
            $t->boolean('acl_billing')->nullable()->default(false); // Can billing see it?
            $t->boolean('acl_support')->nullable()->default(false); // Can support see it? (admin always yes)
            $t->timestamp('expires')->nullable();                   // Optionally we can self-destruct.
            $t->boolean('active')->nullable()->default(true);       // Expired?
            $t->integer('views')->nullable()->default(0);           // When a request for this file is made inc the counter.
            $t->integer('auth_required')->nullable()->default(1);   // Requires you to be logged in to view?
            $t->string('mime_type')->nullable();
            $t->bigInteger('filesize')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lo_files');
    }
};
