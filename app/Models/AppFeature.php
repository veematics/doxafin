<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}