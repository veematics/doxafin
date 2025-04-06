<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $primaryKey = 'feature_id';
    protected $fillable = ['feature_code', 'feature_name', 'description'];

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'feature_id');
    }
}