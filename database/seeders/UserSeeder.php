<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['id' => 1, 'name' => 'User 1', 'email' => 'user1@example.com', 'password' => Hash::make('password')],
            ['id' => 2, 'name' => 'User 2', 'email' => 'user2@example.com', 'password' => Hash::make('password')],
            ['id' => 3, 'name' => 'User 3', 'email' => 'user3@example.com', 'password' => Hash::make('password')],
            ['id' => 4, 'name' => 'User 4', 'email' => 'user4@example.com', 'password' => Hash::make('password')],
            ['id' => 5, 'name' => 'User 5', 'email' => 'user5@example.com', 'password' => Hash::make('password')],
            ['id' => 8, 'name' => 'User 8', 'email' => 'user8@example.com', 'password' => Hash::make('password')],
            ['id' => 10, 'name' => 'User 10', 'email' => 'user10@example.com', 'password' => Hash::make('password')],
            ['id' => 11, 'name' => 'User 11', 'email' => 'user11@example.com', 'password' => Hash::make('password')],
            ['id' => 12, 'name' => 'User 12', 'email' => 'user12@example.com', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            if (!DB::table('users')->where('id', $user['id'])->exists()) {
                DB::table('users')->insert([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}