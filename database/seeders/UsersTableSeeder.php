<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Get all current users
        $users = DB::table('users')->get();
        
        // Clear the table first
        DB::table('users')->truncate();
        
        // Insert static data based on current users
        $staticUsers = [];
        foreach ($users as $user) {
            $staticUsers[] = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password, // Keep existing hashed passwords
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                // Add other fields as needed
            ];
        }

        // Insert the static data
        DB::table('users')->insert($staticUsers);
        
        // Output the static data array for reference
        $this->command->info('Static user data:');
        $this->command->info(json_encode($staticUsers, JSON_PRETTY_PRINT));
    }
}
