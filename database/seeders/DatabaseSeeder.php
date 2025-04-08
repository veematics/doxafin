<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    // In DatabaseSeeder.php
public function run()
{
    $this->call([
        RolesTableSeeder::class,
        AppfeaturesTableSeeder::class,
        FeatureRoleTableSeeder::class,
        RoleUserTableSeeder::class,
        UsersTableSeeder::class,
    ]);
}
}
