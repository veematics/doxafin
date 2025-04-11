<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Menu;
use App\Helpers\FeatureAccess;

class SidebarMenu extends Component
{
    public $menuItems;

    public function __construct()
    {
        // Force clear cache for debugging
        Cache::forget('sidebar_menu_items');

        $this->menuItems = Cache::rememberForever('sidebar_menu_items', function () {
            $items = Menu::where('name', 'Sidebar Menu')
                ->with(['menuItems' => function ($query) {
                    $query->orderBy('order')
                        ->with(['children' => function ($q) {
                            $q->orderBy('order');
                        }]);
                }])
                ->first()
                ->menuItems()
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

            // Add debug output for filtering
            foreach ($items as $item) {
                Log::info("Filtering menu item:", [
                    'item_id' => $item->id,
                    'feature_id' => $item->app_feature_id,
                    'has_permission' => $this->checkMenuPermission($item)
                ]);
            }

            // Filter items based on permissions
            return $items->filter(function ($item) {
                $hasPermission = $this->checkMenuPermission($item);
                
                if ($item->children->count() > 0) {
                    // Filter children and remove parent if no visible children
                    $item->children = $item->children->filter(function ($child) {
                        $childPermission = $this->checkMenuPermission($child);
                        Log::info("Filtering child menu item:", [
                            'child_id' => $child->id,
                            'feature_id' => $child->app_feature_id,
                            'has_permission' => $childPermission
                        ]);
                        return $childPermission;
                    });
                    
                    // Return true only if parent has permission or has visible children
                    return $hasPermission || $item->children->count() > 0;
                }
                
                return $hasPermission;
            });
        });
    }

    protected function checkMenuPermission($menuItem)
    {
        if (!$menuItem->app_feature_id) {
            return true;
        }

        $featureId = $menuItem->app_feature_id;
        $userId = auth()->id();
        
        // Get all roles and check if any role has permission
        $roles = auth()->user()->roles()->pluck('name')->toArray();
        foreach ($roles as $role) {
            if (FeatureAccess::canViewById($userId, $featureId)) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return view('components.sidebar-menu');
    }
}