<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id('logID');
            $table->string('logCategory', 50)->default('0');
            $table->string('logAction', 50)->default('0');
            $table->integer('logObjID')->default(0);
            $table->string('logBy', 50)->default('0');
            $table->text('logNotes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};