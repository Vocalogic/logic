<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->string('type');
            $table->unsignedInteger('type_id');
            $table->smallInteger('log_level')->index();
            $table->string('log');
            $table->text('detail')->nullable();
        });
    }
};
