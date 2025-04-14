<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => 6,
                'name' => 'Sidebar Menu',
                'description' => 'Main sidebar navigation menu',
                'created_at' => '2025-04-07 13:28:27',
                'updated_at' => '2025-04-07 13:28:27'
            ],
            [
                'id' => 7,
                'name' => 'Personal Menu',
                'description' => 'Personal user navigation menu',
                'created_at' => '2025-04-07 13:28:27',
                'updated_at' => '2025-04-07 13:28:27'
            ],
            [
                'id' => 8,
                'name' => 'Super Admin',
                'description' => 'Superadmin',
                'created_at' => '2025-04-07 13:28:27',
                'updated_at' => '2025-04-07 13:28:27'
            ]
        ];

        DB::table('menus')->insert($menus);
    }
}