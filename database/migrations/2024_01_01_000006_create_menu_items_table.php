<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->enum('item_type', ['feature', 'free_form']);
            $table->unsignedInteger('order')->default(0);
            $table->string('title');
            $table->string('icon', 100)->nullable();
            $table->string('path', 2048)->nullable();
            $table->string('target', 50)->default('_self');
            $table->unsignedBigInteger('app_feature_id')->nullable();
            $table->json('custom_data')->nullable();
            $table->timestamps();

            $table->index('menu_id');
            $table->index('parent_id');
            $table->index('app_feature_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};