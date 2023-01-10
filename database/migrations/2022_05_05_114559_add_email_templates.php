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
        Schema::create('email_template_categories', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');
        });

        Schema::create('email_templates', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('email_template_category_id');
            $t->string('ident');             // email.quote, etc.
            $t->string('name');              // Name of Template
            $t->string('description', 1024); // What does this template do
            $t->string('subject');           // Subject of email
            $t->text('help')->nullable();    // Provide keys that can be used.
            $t->text('body');                // Content used.
        });

        \App\Models\EmailTemplateCategory::create(['name' => "Finance"]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_template_categories');
        Schema::drop('email_templates');
    }
};
