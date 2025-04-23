<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_po', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poID');
            $table->string('serviceName');
            $table->date('serviceStartDate');
            $table->date('serviceEndDate');
            $table->timestamps();

            $table->foreign('poID')->references('poID')->on('purchase_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_po');
    }
};