<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get all menu items for this menu
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Get only top-level menu items in order
     */
    public function topLevelItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('order');
    }

    /**
     * Find a menu by its slug
     */
    public static function findBySlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get menus by type
     */
    public static function getByType(string $type)
    {
        return static::where('type', $type)->get();
    }
}