<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'item_type',
        'order',
        'title',
        'icon',
        'path',
        'target',
        'app_feature_id',
        'custom_data',
    ];

    protected $casts = [
        'custom_data' => 'array',
    ];

    /**
     * Get the menu that owns the item
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the parent menu item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the children menu items
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the associated app feature
     */
    public function appFeature(): BelongsTo
    {
        return $this->belongsTo(AppFeature::class, 'app_feature_id');
    }

    /**
     * Get the full URL for this menu item
     */
    public function getUrlAttribute(): ?string
    {
        if ($this->item_type === 'feature' && $this->appFeature) {
            return $this->appFeature->path;
        }
        
        return $this->path;
    }

    /**
     * Check if the menu item has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all descendants of the menu item
     */
    public function getAllChildren()
    {
        return $this->children()->with('children');
    }

    /**
     * Reorder siblings
     */
    public static function reorder(array $items, $parentId = null)
    {
        foreach ($items as $order => $itemId) {
            static::where('id', $itemId)->update([
                'parent_id' => $parentId,
                'order' => $order
            ]);
        }
    }
}