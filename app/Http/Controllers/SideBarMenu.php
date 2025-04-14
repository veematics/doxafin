<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SideBarMenu extends Controller
{
    public function index()
    {
        $menuItems = [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
            ],
            [
                'title' => 'Reports',
                'icon' => 'fas fa-chart-bar',
                'route' => 'reports',
            ],
            [
                'title' => 'Settings',
                'icon' => 'fas fa-cog',
                'route' => 'settings',
            ],
        ];

        return view('components.sidebar-menu', compact('menuItems'));
    }
}