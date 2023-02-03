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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');                                 // Affiliate Name
            $table->string('company')->nullable();                  // Optional Company Name
            $table->string('email');                                // Email is required.
            $table->double('mrr')->nullable();                      // Percentage Commission for MRR Services
            $table->integer('spiff')->nullable();                   // One Time Spiff of MRR.
            $table->text('notes')->nullable();                      // Optional notes about this affiliate
            $table->boolean('active')->nullable()->default(true);   // Disable affiliate coupons if false
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
};
