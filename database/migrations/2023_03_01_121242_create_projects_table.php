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
        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->timestamps();
            $t->integer('account_id')->nullable();
            $t->integer('lead_id')->nullable();
            $t->string('name');                     // Create an app
            $t->string('description');              // Short Description of Project.
            $t->string('status')->default('Draft'); // Status of Project
            $t->integer('leader_id');               // Who created project / leading it
            $t->timestamp('sent_on')->nullable();
            $t->timestamp('due_on')->nullable();
            $t->timestamp('approved_on')->nullable();
            $t->text('summary')->nullable();             // Define what the customer wants
            $t->string('bill_method')->default('Mixed'); // Static Price, Hourly, Mixed
            $t->bigInteger('static_price')->nullable();  // If static
        });

        Schema::create('project_categories', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('project_id');
            $t->string('name');
            $t->text('description')->nullable();
            $t->string('bill_method')->default('Mixed'); // Static Price, Hourly, Mixed
            $t->bigInteger('static_price')->nullable();  // If static (hourly gets from tasks)
        });

        Schema::create('project_tasks', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('project_id');
            $t->integer('project_category_id')->nullable();
            $t->string('name');                         // Create a thing
            $t->text('description')->nullable();        // Any deatils for this task.
            $t->double('est_hours')->nullable();        // 0.5 - 2.5.. whatever
            $t->integer('hourly_item_id')->nullable();  // Link to a billitem for hourly billables.
            $t->bigInteger('static_price')->nullable(); // If static - not hourly
            // if a static and a hourly item is set, we can compare price between est_hours * hourly and static
            $t->string('status')->default('Draft');     // Status of Task
            $t->integer('assigned_id')->nullable();     // Who is working it?
        });

        Schema::create('project_task_entries', function($t)
        {
            $t->id();
            $t->timestamps();
            $t->integer('project_task_id');
            $t->double('hours');
            $t->integer('user_id');
            $t->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_categories');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('project_task_entries');


    }
};
