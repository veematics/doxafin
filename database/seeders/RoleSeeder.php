<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'AE',
                'display_name' => 'Account Executive',
                'description' => null,
                'created_at' => '2025-04-07 16:01:03',
                'updated_at' => '2025-04-07 16:01:03',
            ],
            [
                'id' => 2,
                'name' => 'FIN',
                'display_name' => 'Finance and Accounting',
                'description' => null,
                'created_at' => '2025-04-07 16:45:35',
                'updated_at' => '2025-04-07 16:45:55',
            ],
            [
                'id' => 3,
                'name' => 'SA',
                'display_name' => 'Super Admin',
                'description' => null,
                'created_at' => '2025-04-07 18:34:33',
                'updated_at' => '2025-04-07 18:34:33',
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}