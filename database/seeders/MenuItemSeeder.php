<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = [
            [
                'id' => 33,
                'menu_id' => 8,
                'parent_id' => null,
                'item_type' => 'feature',
                'order' => 0,
                'title' => 'App Setup',
                'icon' => 'cil-settings',
                'path' => 'appsetting/appsetup',
                'target' => '_self',
                'app_feature_id' => 4,
                'custom_data' => null,
                'created_at' => '2025-04-10 22:01:43',
                'updated_at' => '2025-04-10 22:01:43'
            ],
            [
                'id' => 34,
                'menu_id' => 8,
                'parent_id' => null,
                'item_type' => 'feature',
                'order' => 1,
                'title' => 'Feature Management',
                'icon' => 'cil-featured-playlist',
                'path' => 'appsetting/appfeature',
                'target' => '_self',
                'app_feature_id' => 5,
                'custom_data' => null,
                'created_at' => '2025-04-10 22:01:43',
                'updated_at' => '2025-04-10 22:01:43'
            ],
            // ... continuing with all menu items
            [
                'id' => 68,
                'menu_id' => 6,
                'parent_id' => 66,
                'item_type' => 'free_form',
                'order' => 1,
                'title' => 'Payment List',
                'icon' => 'cil-list',
                'path' => '/payment/list',
                'target' => '_self',
                'app_feature_id' => 11,
                'custom_data' => null,
                'created_at' => '2025-04-11 10:55:45',
                'updated_at' => '2025-04-11 10:55:45'
            ]
        ];

        DB::table('menu_items')->insert($menuItems);
    }
}