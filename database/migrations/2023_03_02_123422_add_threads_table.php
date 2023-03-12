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
        Schema::create('threads', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('user_id'); // Thread created by
            $t->string('type');     // Enum for Thread Types (Support, Project, etc)
            $t->integer('refid');   // ID of the Model we are using for the thread.
        });

        Schema::create('thread_comments', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('thread_id');                         // Thread ID for comment
            $t->integer('thread_comment_id')->nullable();     // If nested comment
            $t->integer('user_id');                           // User who made the comment.
            $t->text('comment');                              // The actual comment
            $t->boolean('public')->nullable()->default(true); // Can customer see this?
        });

        Schema::create('thread_comment_files', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('comment_id');   // Thread ID for comment
            $t->integer('user_id');     // User who made the comment.
            $t->integer('file_id');     // Id to the file
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_comment_files');
        Schema::dropIfExists('thread_comments');
        Schema::dropIfExists('threads');
    }
};
