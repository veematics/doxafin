<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Get existing data from the database
        $existingMenus = DB::table('appfeatures')->get();

        // Prepare the data for seeding
        $menuData = [];
        foreach ($existingMenus as $menu) {
            $menuData[] = [
                'featureName' => $menu->featureName,
                'featureIcon' => $menu->featureIcon,
                'featurePath' => $menu->featurePath,
                'featureActive' => $menu->featureActive,
                'custom_permission' => $menu->custom_permission,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the data
        DB::table('appfeatures')->insert($menuData);
    }
}
