<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppFeature extends Model
{
    protected $table = 'appfeatures';
    protected $primaryKey = 'featureID';
    
    protected $fillable = [
        'featureName',
        'featureIcon',
        'featurePath',
        'featureActive'
    ];
}