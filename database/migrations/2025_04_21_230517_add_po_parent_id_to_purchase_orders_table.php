<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('poParentID')->nullable()->after('poID');
            $table->foreign('poParentID')->references('poID')->on('purchase_orders')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['poParentID']);
            $table->dropColumn('poParentID');
        });
    }
};
