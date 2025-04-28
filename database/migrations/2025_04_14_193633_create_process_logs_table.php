<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('process_logs', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->unsignedBigInteger('objectID');
            $table->unsignedBigInteger('featureID');
            $table->string('logSubject');
            $table->text('logMessage');
            $table->timestamp('created_at');
            $table->unsignedBigInteger('created_by');
            
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('featureID')->references('featureID')->on('appfeatures');
        });
    }

    public function down()
    {
        Schema::dropIfExists('process_logs');
    }
};