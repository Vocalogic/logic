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
        Schema::create('project_category_items', function ($t) {
            $t->id();
            $t->timestamps();
            $t->integer('project_category_id');
            $t->integer('bill_item_id')->nullable();
            $t->string('code');
            $t->string('name');
            $t->string('description', 2048)->nullable();
            $t->bigInteger('price');
            $t->double('qty')->nullable()->default(1);
            $t->integer('user_id');    // Who added this
            $t->bigInteger('expense'); // Total Expense.
            $t->string('expense_description')->nullable();
            $t->integer('invoice_id')->nullable(); // Which invoice did this get billed out on?
            $t->string('bill_type')->nullable()->default('Start'); // Start, During , Completed
            $t->string('notes', 2048)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_category_items');
    }
};
