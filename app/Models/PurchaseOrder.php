<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $primaryKey = 'poID';
    
    protected $fillable = [
        'poNo',
        'poClient',
        'poStatus',
        'poTerm',
        'poValue',
        'poCurrency',
        'poStartDate',
        'poEndDate',
        'poFiles',
        'created_by'
    ];

    protected $casts = [
        'poStartDate' => 'date',
        'poEndDate' => 'date',
        'poFiles' => 'json'
    ];

    public static $poStatus = ['Draft', 'Pending', 'Approved', 'Rejected'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'poClient');
    }

    public function serviceItems()
    {
        return $this->hasMany(ServicePo::class, 'poID');
    }

    // Add new relationship for invoices
    public function invoices()
    {
        return $this->hasMany(InvoicePo::class, 'invoicepoID');
    }

    // Add method to check if PO can be deleted
    public function canDelete()
    {
        return $this->invoices()->count() === 0;
    }
}
