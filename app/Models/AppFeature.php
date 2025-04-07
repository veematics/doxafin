<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppFeature extends Model
{
    protected $table = 'appfeatures';
    protected $primaryKey = 'featureID';
    
    protected $fillable = [
        'featureName',
        'featureIcon',
        'featurePath',
        'featureActive',
        'custom_permission'
    ];

    protected $casts = [
        'featureActive' => 'boolean',
        'custom_permission' => 'array'
    ];
    
    /**
     * Get the menu items associated with this feature
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'app_feature_id');
    }

    /**
     * Get features available for menu items
     */
    public static function getAvailableForMenu()
    {
        return static::where('active', true)
            ->orderBy('feature_name')
            ->get();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'feature_role',
            'feature_id',
            'role_id'
        )->withPivot([
            'can_view',
            'can_create',
            'can_edit',
            'can_delete',
            'additional_permissions'
        ])->withTimestamps();
    }
}