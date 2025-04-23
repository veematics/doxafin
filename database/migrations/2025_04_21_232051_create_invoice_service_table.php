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
        Schema::create('invoice_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoiceID');
            $table->unsignedBigInteger('serviceID');
            $table->decimal('value', 14, 2);
            $table->string('status');
            $table->timestamps();

            $table->foreign('invoiceID')->references('invoiceID')->on('invoice_po')->onDelete('cascade');
            $table->foreign('serviceID')->references('id')->on('service_po')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_service');
    }
};