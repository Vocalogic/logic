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
        Schema::create('package_builds', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->string('name');                            // Phone Service
            $t->string('description', 2048)->nullable();   // Get phone service today!
            $t->boolean('active')->nullable()->default(1); // Show this on the shop?
        });

        Schema::create('package_sections', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('package_build_id');
            $t->string('name'); // Requirements Gathering
            $t->boolean('default_show')->nullable()->default(1);
            $t->integer('unless_question_id')->nullable(); // Show this section if something
            $t->string('question_equates')->nullable();    // = > <
            $t->string('question_equates_to')->nullable(); // Answer
        });

        Schema::create('package_section_questions', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('package_section_id');
            $t->string('question');                               // How many extensions
            $t->string('type')->nullable()->default('single');    // single/multi/dropdown/etc
            $t->boolean('is_numeric')->nullable()->default(true); // Is answer numerical?
            $t->integer('qty_from_answer_id')->nullable();        // Base number of times questions asked based on qty
            $t->boolean('default_show')->nullable()->default(1);
            $t->integer('unless_question_id')->nullable(); // Show this question if something
            $t->string('question_equates')->nullable();    // = > <
            $t->string('question_equates_to')->nullable(); // Answer
        });

        Schema::create('package_section_question_options', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('package_section_question_id');
            $t->string('option');                        // Could be a question for MI or an option in a dropdown depending on q type
            $t->string('description', 2048)->nullable(); // Help on a multi-input
        });

        Schema::create('package_section_question_logics', function ($t) {
            $t->increments('id');
            $t->timestamps();
            $t->integer('package_section_question_id');    // Relates to this question
            $t->string('answer_equates');                  // =, <> , exists, does not exist, etc.
            $t->string('answer');                          // The answer given to the question.
            $t->integer('add_item_id');                    // Add an item to a cart
            $t->integer('qty_from_answer');                // Add qty based on answer?
            $t->integer('qty')->nullable();                // Add forced qty period.
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_builds');
        Schema::dropIfExists('package_sections');
        Schema::dropIfExists('package_section_questions');
        Schema::dropIfExists('package_section_question_options');
        Schema::dropIfExists('package_section_question_logics');
    }
};
