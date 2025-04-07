<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('appfeatures', 'featureActive')) {
            Schema::table('appfeatures', function (Blueprint $table) {
                $table->boolean('featureActive')->default(1)->after('featurePath');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('appfeatures', 'featureActive')) {
            Schema::table('appfeatures', function (Blueprint $table) {
                $table->dropColumn('featureActive');
            });
        }
    }
};