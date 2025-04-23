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
        Schema::create('payment_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoiceID');
            $table->decimal('paymentValue', 14, 2);
            $table->date('paymentDate');
            $table->string('status');
            $table->timestamps();

            $table->foreign('invoiceID')->references('invoiceID')->on('invoice_po')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_invoice');
    }
};