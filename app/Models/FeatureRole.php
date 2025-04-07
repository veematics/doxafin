<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureRole extends Model
{
    protected $table = 'feature_role';
    
    protected $fillable = [
        'role_id',
        'feature_id',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
        'additional_permissions'
    ];

    protected $casts = [
        'additional_permissions' => 'array'
    ];
}