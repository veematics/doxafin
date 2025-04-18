<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appfeatures', function (Blueprint $table) {
            $table->id('featureID');
            $table->string('featureName');
            $table->string('featureDescription')->nullable();
            $table->string('featureIcon');
            $table->string('featurePath');
            $table->boolean('featureActive')->default(true);
            $table->text('custom_permission')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appfeatures');
    }
};