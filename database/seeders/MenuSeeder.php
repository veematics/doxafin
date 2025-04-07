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
                'id' => 1,
                'name' => 'Sidebar Menu',
                'description' => 'Main sidebar navigation menu',
                'created_at' => '2025-04-07 05:01:31',
                'updated_at' => '2025-04-07 05:01:31'
            ],
            [
                'id' => 2,
                'name' => 'Personal Menu',
                'description' => 'Personal user navigation menu',
                'created_at' => '2025-04-07 05:01:31',
                'updated_at' => '2025-04-07 05:01:31'
            ],
            [
                'id' => 4,
                'name' => 'Super Admin',
                'description' => 'Superadmin',
                'created_at' => '2025-04-07 08:30:46',
                'updated_at' => '2025-04-07 08:30:46'
            ]
        ];

        $menuItems = [
            [
                'id' => 246,
                'menu_id' => 2,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 0,
                'title' => 'Profile',
                'icon' => 'cil-user',
                'path' => '/profile',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => '2025-04-07 11:18:47',
                'updated_at' => '2025-04-07 11:18:47'
            ],
            [
                'id' => 251,
                'menu_id' => 4,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 0,
                'title' => 'App Setup',
                'icon' => 'cil-settings',
                'path' => '/appsetting/appsetup',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => '2025-04-07 11:53:41',
                'updated_at' => '2025-04-07 11:53:41'
            ],
            [
                'id' => 252,
                'menu_id' => 4,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 1,
                'title' => 'User Management',
                'icon' => 'cil-people',
                'path' => 'appsetting/users',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => '2025-04-07 11:53:41',
                'updated_at' => '2025-04-07 11:53:41'
            ],
            [
                'id' => 253,
                'menu_id' => 4,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 2,
                'title' => 'Feature',
                'icon' => 'cil-featured-playlist',
                'path' => '/appsetting/appfeature',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => '2025-04-07 11:53:41',
                'updated_at' => '2025-04-07 11:53:41'
            ],
            [
                'id' => 254,
                'menu_id' => 4,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 3,
                'title' => 'Menu Builder',
                'icon' => 'cil-menu',
                'path' => '/appsetting/menu',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => '2025-04-07 11:53:41',
                'updated_at' => '2025-04-07 11:53:41'
            ],
            [
                'id' => 255,
                'menu_id' => 4,
                'parent_id' => null,
                'item_type' => 'free_form',
                'order' => 4,
                'title' => 'Territories',
                'icon' => 'cil-vector',
                'path' => '/appsetting/territories',
                'target' => '_self',
                'app_feature_id' => null,
                'custom_data' => null,
                'created_at' => '2025-04-07 11:53:41',
                'updated_at' => '2025-04-07 11:53:41'
            ]
        ];

        DB::table('menus')->insert($menus);
        DB::table('menu_items')->insert($menuItems);
    }
}