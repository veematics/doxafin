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
        'poTerm'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'poClient');
    }
}
