<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Menu;
use App\Models\MenuItem;

class PersonalMenu extends Component
{
    public $menuItems;

    public function __construct()
    {
        $menu = Menu::where('name', 'Personal Menu')->first();
        $this->menuItems = $menu ? $menu->menuItems()->orderBy('order')->get() : collect();
    }

    public function render()
    {
        return view('components.personal-menu');
    }
}