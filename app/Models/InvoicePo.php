<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePo extends Model
{
    protected $table = 'invoice_po';
    protected $primaryKey = 'invoiceID';
    
    protected $fillable = [
        'invoicepoID',
        'invoiceNo',
        'InvoiceValue',
        'created_by',
        'status'
    ];

    protected $casts = [
        'InvoiceValue' => 'decimal:2'
    ];

    // Only set created_at timestamp, updated_at is not in the table
    const UPDATED_AT = null;

    // Relationship with Purchase Order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'invoicepoID', 'poID');
    }

    // Relationship with User who created the invoice
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}