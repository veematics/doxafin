<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePo extends Model
{
    protected $table = 'service_po';

    protected $fillable = [
        'poID',
        'serviceName',
        'serviceValue',
        'serviceStartDate',
        'serviceEndDate',

        // Add other relevant fields here
    ];
    protected $casts = [
        'serviceStartDate' => 'date',
        'serviceEndDate' => 'date'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'poID');
    }
}