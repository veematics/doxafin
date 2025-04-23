<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('service_po', function (Blueprint $table) {
            $table->decimal('serviceValue', 12, 2)->after('serviceName');
        });
    }

    public function down()
    {
        Schema::table('service_po', function (Blueprint $table) {
            $table->dropColumn('serviceValue');
        });
    }
};