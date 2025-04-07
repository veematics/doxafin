<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Sidebar Menu',
                'slug' => 'sidebar-menu',
                'type' => 'sidebar',
                'description' => 'Main sidebar navigation menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Personal Menu',
                'slug' => 'personal-menu',
                'type' => 'personal',
                'description' => 'Personal user navigation menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('menus')->insert($menus);
    }
}