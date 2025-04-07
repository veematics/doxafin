<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('appfeatures', 'custom_permission')) {
            Schema::table('appfeatures', function (Blueprint $table) {
                $table->text('custom_permission')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('appfeatures', 'custom_permission')) {
            Schema::table('appfeatures', function (Blueprint $table) {
                $table->dropColumn('custom_permission');
            });
        }
    }
};