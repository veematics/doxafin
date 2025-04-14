<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $roleUsers = [
            [
                'id' => 15,
                'role_id' => 2,
                'user_id' => 3,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 16,
                'role_id' => 2,
                'user_id' => 4,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 18,
                'role_id' => 2,
                'user_id' => 8,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 19,
                'role_id' => 2,
                'user_id' => 10,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 20,
                'role_id' => 2,
                'user_id' => 11,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 21,
                'role_id' => 2,
                'user_id' => 12,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 22,
                'role_id' => 3,
                'user_id' => 1,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 25,
                'role_id' => 1,
                'user_id' => 2,
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 26,
                'role_id' => 2,
                'user_id' => 2,
                'created_at' => null,
                'updated_at' => null
            ],
        ];

        DB::table('role_user')->insert($roleUsers);
    }
}