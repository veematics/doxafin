<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Menu;
use App\Models\MenuItem;

class SuperAdminMenu extends Component
{
    public $menuItems;

    public function __construct()
    {
        $menu = Menu::where('name', 'Super Admin')->first();
        $this->menuItems = $menu ? $menu->menuItems()->orderBy('order')->get() : collect();
    }

    public function render()
    {
        return view('components.super-admin-menu');
    }
}