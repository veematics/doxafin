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
        Schema::create('notes', function (Blueprint $table) {
            $table->id('notesID');
            $table->string('notesCategory', 50)->nullable();
            $table->integer('notesObjID')->nullable();
            $table->string('notesTitle', 50)->nullable();
            $table->text('notesSummary')->nullable();
            $table->text('notesDetail')->nullable();

            $table->string('notesBy', 50)->nullable();
            $table->tinyInteger('notesHide')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};