<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appfeatures')) {
            Schema::create('appfeatures', function (Blueprint $table) {
                $table->id('featureID');
                $table->string('featureName');
                $table->string('featureIcon');
                $table->string('featurePath');
                $table->boolean('featureActive')->default(true);
                $table->text('custom_permission')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('appfeatures', function (Blueprint $table) {
                if (!Schema::hasColumn('appfeatures', 'featureActive')) {
                    $table->boolean('featureActive')->default(true);
                }
                if (!Schema::hasColumn('appfeatures', 'custom_permission')) {
                    $table->text('custom_permission')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appfeatures');
    }
};