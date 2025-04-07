<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            [
                'name' => 'Sidebar Menu',
                'description' => 'Main sidebar navigation menu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Personal Menu',
                'description' => 'Personal user navigation menu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Super Admin',
                'description' => 'Superadmin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('menus')->insert($menus);

        // Get the inserted menu IDs
        $personalMenuId = DB::table('menus')->where('name', 'Personal Menu')->first()->id;
        $superAdminMenuId = DB::table('menus')->where('name', 'Super Admin')->first()->id;

        $menuItems = [
            [
                'menu_id' => $personalMenuId,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 0,
                'title' => 'Profile',
                'icon' => 'cil-user',
                'path' => '/profile',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_id' => $superAdminMenuId,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 0,
                'title' => 'App Setup',
                'icon' => 'cil-settings',
                'path' => '/appsetting/appsetup',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_id' => $superAdminMenuId,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 1,
                'title' => 'User Management',
                'icon' => 'cil-people',
                'path' => 'appsetting/users',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_id' => $superAdminMenuId,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 2,
                'title' => 'Feature',
                'icon' => 'cil-featured-playlist',
                'path' => '/appsetting/appfeature',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_id' => $superAdminMenuId,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 3,
                'title' => 'Menu Builder',
                'icon' => 'cil-menu',
                'path' => '/appsetting/menu',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_id' => $superAdminMenuId,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 4,
                'title' => 'Territories',
                'icon' => 'cil-vector',
                'path' => '/appsetting/territories',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('menu_items')->insert($menuItems);
    }
}