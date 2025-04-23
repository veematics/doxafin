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
}
