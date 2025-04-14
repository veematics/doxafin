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
        $noCache = Request::get('no_cache') == 1;

        if ($noCache) {
            $this->menuItems = $this->getSidebarMenuItems();
            Cache::put("sidebar_menu_items.{$userId}", $this->menuItems, now()->addDay());
        } else {
            $this->menuItems = Cache::remember("sidebar_menu_items.{$userId}", now()->addDay(), function () {
                return $this->getSidebarMenuItems();
            });
        }
    }

    private function getSidebarMenuItems()
    {
        // Get parent menu items for Sidebar Menu
        $menuItems = DB::table('menus')
            ->join('menu_items', 'menus.id', '=', 'menu_items.menu_id')
            ->where('menus.name', '=', 'Sidebar Menu')
            ->whereNull('menu_items.parent_id')
            ->select('menu_items.*')
            ->orderBy('menu_items.order')
            ->get();

        return $menuItems->map(function ($item) {
            // Get child menu items for each parent
            $children = DB::table('menu_items')
                ->where('parent_id', $item->id)
                ->orderBy('order')
                ->get();

            return [
                'id' => $item->id,
                'title' => $item->title,
                'path' => $item->path,
                'icon' => $item->icon,
                'children' => $children
            ];
        });
    }

    public function render()
    {
        return view('components.sidebar-menu');
    }
}