<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('appfeatures', 'Featuredescription')) {
            Schema::table('appfeatures', function (Blueprint $table) {
                $table->string('Featuredescription')->nullable()->after('featureName');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('appfeatures', 'Featuredescription')) {
            Schema::table('appfeatures', function (Blueprint $table) {
                $table->dropColumn('Featuredescription');
            });
        }
    }
};