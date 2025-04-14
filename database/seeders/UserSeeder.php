<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Viktor Iwan',
                'email' => 'viktor.iwan@doxadigital.com',
                'avatar' => '1744009314_67f3786202cb9.jpeg',
                'email_verified_at' => null,
                'password' => '$2y$12$/KhKP8OdxwsUvvrjq31SdeYrrpj82dqSZOCVEJanzUD2qNuDaj9su',
                'remember_token' => null,
                'is_active' => 1,
                'created_at' => '2025-04-03 03:46:14',
                'updated_at' => '2025-04-07 07:04:09'
            ],
            [
                'id' => 2,
                'name' => 'Ratna Anni',
                'email' => 'ratna@doxadigital.com',
                'avatar' => null,
                'email_verified_at' => null,
                'password' => '$2y$12$5i1pvS9i3DtBTY1sZ8X8hupJGVhCUH4UMG.epccHXii/hWO/ENL86',
                'remember_token' => null,
                'is_active' => 1,
                'created_at' => '2025-04-07 06:41:34',
                'updated_at' => '2025-04-07 06:41:47'
            ],
            // Add all other users here...
        ];

        DB::table('users')->insert($users);
    }
}