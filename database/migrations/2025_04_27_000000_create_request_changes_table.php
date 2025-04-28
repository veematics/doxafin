<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_changes', function (Blueprint $table) {
            $table->id();
            $table->morphs('changeable'); // Creates changeable_type and changeable_id for polymorphic relationship
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'request-revision','approved','rejected'])->default('pending');
            $table->json('changes'); // Store the changes in JSON format
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_changes');
    }
};