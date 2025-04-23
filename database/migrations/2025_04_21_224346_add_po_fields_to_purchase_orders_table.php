<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('poValue', 14, 2)->before('created_at');
            $table->string('poCurrency', 3)->before('created_at');
            $table->string('poType')->before('created_at');
            $table->date('poStartDate')->before('created_at');
            $table->date('poEndDate')->before('created_at');
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['poValue', 'poCurrency', 'poType', 'poStartDate', 'poEndDate']);
        });
    }
};