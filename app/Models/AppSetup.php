<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetup extends Model
{
    protected $primaryKey = 'AppsID';
    
    protected $fillable = [
        'AppsName',
        'AppsTitle',
        'AppsSubTitle',
        'AppsLogo',
        'AppsShortLogo',
    ];
}