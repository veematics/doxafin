<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SidebarMenu extends Component
{
    public $menuItems;

    public function __construct()
    {
        $userId = Auth::id();
        $cacheKey = "sidebar_menu_{$userId}";
        $noCache = Request::get('no_cache') == 1;

        if ($noCache) {
            $this->menuItems = $this->getSidebarMenuItems();
            Cache::put($cacheKey, $this->menuItems, now()->addDay());
        } else {
            $this->menuItems = Cache::remember($cacheKey, now()->addDay(), function () use ($userId) {
                return $this->getSidebarMenuItems($userId);
            });
        }
    }

    private function getSidebarMenuItems($userId)
    {
        // Get user permissions from cache
        $userPermissions = Cache::get('user_permissions_' . $userId, []);

        // Get parent menu items for Sidebar Menu
        $menuItems = DB::table('menus')
            ->join('menu_items', 'menus.id', '=', 'menu_items.menu_id')
            ->where('menus.name', '=', 'Sidebar Menu')
            ->whereNull('menu_items.parent_id')
            ->select('menu_items.*')
            ->orderBy('menu_items.order')
            ->get();

        return $menuItems->map(function ($item) use ($userPermissions) {
            // Check user permission for this menu item
            $canView = $this->checkPermission($item->app_feature_id, $userPermissions);

            // Skip this menu item if user doesn't have permission
            if (!$canView) {
                return null;
            }

            // Get child menu items for each parent
            $children = DB::table('menu_items')
                ->where('parent_id', $item->id)
                ->orderBy('order')
                ->get()
                ->filter(function ($child) use ($userPermissions) {
                    return $this->checkPermission($child->app_feature_id, $userPermissions);
                });

            return [
                'id' => $item->id,
                'title' => $item->title,
                'path' => $item->path,
                'icon' => $item->icon,
                'children' => $children
            ];
        })->filter(); // Remove null items (skipped due to permissions)
    }

    private function checkPermission($featureId, $userPermissions)
    {
        if ($featureId === null) {
            return true; // Set 'can_view' directly to 1 if feature_id is null
        }

        // Check 'can_view' parameter from user_permissions cache
        return isset($userPermissions[$featureId]['can_view']) && $userPermissions[$featureId]['can_view'] >= 1;
    }

    public function render()
    {
        return view('components.sidebar-menu');
    }
}