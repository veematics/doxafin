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
<<<<<<< HEAD
            $this->menuItems = $this->getSidebarMenuItems($userId);  // Added missing $userId parameter
          
=======
            $this->menuItems = $this->getSidebarMenuItems($userId);
>>>>>>> 08efe325cdca44eb6765054f06a788b15a786eab
            Cache::put($cacheKey, $this->menuItems, now()->addDay());
        } else {
            $this->menuItems = Cache::remember($cacheKey, now()->addDay(), function () use ($userId) {
                return $this->getSidebarMenuItems($userId);
            });
        }
    }

    private function getSidebarMenuItems($userId)
    {
        $userPermissions = Cache::get('user_permissions_' . $userId, []);

        // Get parent menu items
        $menuItems = DB::table('menus')
            ->join('menu_items', 'menus.id', '=', 'menu_items.menu_id')
            ->where('menus.name', '=', 'Sidebar Menu')
            ->whereNull('menu_items.parent_id')
            ->select('menu_items.*')
            ->orderBy('menu_items.order')
            ->get();

        // First filter out unauthorized parent items
        return $menuItems
            ->filter(function ($item) use ($userPermissions) {
                return $this->checkPermission($item->app_feature_id, $userPermissions);
            })
            ->map(function ($item) use ($userPermissions) {
                // Get and filter child items
                $children = DB::table('menu_items')
                    ->where('parent_id', $item->id)
                    ->orderBy('order')
                    ->get()
                    ->filter(function ($child) use ($userPermissions) {
                        return $this->checkPermission($child->app_feature_id, $userPermissions);
                    });

                // Return formatted menu item
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'path' => $item->path,
                    'icon' => $item->icon,
                    'children' => $children
                ];
            });
    }

    private function checkPermission($featureId, $userPermissions)
    {
        if ($featureId === null) {
            return true; // Set 'can_view' directly to 1 if feature_id is null
        }
<<<<<<< HEAD
       
        return (isset($userPermissions[$featureId][0]->can_view) && ($userPermissions[$featureId][0]->can_view >= 1));
             
=======

        // Check 'can_view' parameter from user_permissions cache
        return isset($userPermissions[$featureId][0]->can_view) && $userPermissions[$featureId][0]->can_view >= 1;
>>>>>>> 08efe325cdca44eb6765054f06a788b15a786eab
    }

    public function render()
    {
        return view('components.sidebar-menu');
    }
}