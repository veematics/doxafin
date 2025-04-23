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
        Schema::create('invoice_po', function (Blueprint $table) {
            $table->id('invoiceID');
            $table->unsignedBigInteger('invoicepoID');
            $table->string('invoiceNo');
            $table->decimal('InvoiceValue', 14, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('created_by');
            $table->string('status');

            $table->foreign('invoicepoID')->references('poID')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_po');
    }
};