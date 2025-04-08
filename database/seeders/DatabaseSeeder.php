<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RolesTableSeeder::class,
            AppfeaturesTableSeeder::class,
            FeatureRoleTableSeeder::class,
            // Remove duplicate RoleUser seeders
            RoleUserTableSeeder::class,
            // Add MenuSeeder
            MenuSeeder::class,
        ]);
    }
}
