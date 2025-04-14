<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            RoleUserSeeder::class,
            AppFeatureSeeder::class,
            AppSetupSeeder::class,
            MenuSeeder::class,
            MenuItemSeeder::class,
        ]);
    }
}
