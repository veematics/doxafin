<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PersonalMenu extends Component
{
    public $menuItems;

    public function __construct()
    {
        $userId = Auth::id();
        $cacheKey = "personal_menu_{$userId}";
        $noCache = Request::get('no_cache') == 1;

        if ($noCache) {
            $this->menuItems = $this->getPersonalMenuItems();
            Cache::put($cacheKey, $this->menuItems, now()->addDay());
        } else {
            $this->menuItems = Cache::remember($cacheKey, now()->addDay(), function () {
                return $this->getPersonalMenuItems();
            });
        }
    }

    private function getPersonalMenuItems()
    {
        $menu = Menu::where('name', 'Personal Menu')->first();
        return $menu ? $menu->menuItems()->orderBy('order')->get() : collect();
    }

    public function render()
    {
        return view('components.personal-menu');
    }
}